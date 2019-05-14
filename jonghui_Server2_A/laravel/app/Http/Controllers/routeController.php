<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use \SplPriorityQueue;


class routeQueue extends SplPriorityQueue
{
	public function compare($a, $b)
	{
		return $b <=> $a;
	}
}

class routeData
{
	public $schedules = [], $visits = [], $time = null;

	public function lastVisits()
	{
		return $this->visits[count($this->visits)-1];
	}
	public function lastSchedules()
	{
		return $this->schedules[count($this->schedules)-1];
	}
	public function isVisit($id)
	{
		return in_array($id, $this->visits);
	}
}

class routeController extends Controller
{

	public $from, $to, $depart, $queue, $memo;

	public function __construct()
	{
		$this->queue = new routeQueue();
		$this->memo = \App\places::get()->keyBy("id");
	}

	public function search($from, $to, $depart = "")
	{
		$this->from = $from;
		$this->to = $to;
		$this->depart = $depart;
		$time = $depart ? $depart : date("H:i:s");

		$list = DB::select("
			SELECT * FROM `schedules`
			WHERE (to_place_id, arrival_time, type) IN (
				SELECT to_place_id, min(arrival_time), type FROM `schedules`
				WHERE `from_place_id` = ? && `departure_time` >= ? && `status` = 'AVAILABLE'
				GROUP BY `to_place_id`, `type`
			)
		", [$from, $time]);

		foreach ($list as $key => $value) {
			$rd = new routeData();

			$value->from_place = $this->memo[$value->from_place_id];
			$value->to_place = $this->memo[$value->to_place_id];
			unset($value->from_place_id, $value->to_place_id);

			$rd->visits = [$value->from_place->id, $value->to_place->id];
			$rd->schedules[] = $value;
			$rd->time = $value->arrival_time;

			$this->routing($rd);
		}

		$result = [];

		while ($this->queue->valid()) {
			$data = $this->queue->current();
			$sch = $data->schedules;

			$result[] = [
				"schedules" => $sch,
				"transfer" => $this->getTransfer($sch)/*collect($sch)->pluck("line")->flip()->count()*/,
				"total_travel" => $this->getTravel($sch)
			];


			$this->queue->next();
		}

		$user = \App\users::cur();

		$his = \App\histories::whereUserId($user->id)->wherePlaceId($from)->first();
		$his2 = \App\histories::whereUserId($user->id)->wherePlaceId($to)->first();

		if ($his) $his->update(['count' => $his->count+1]);
		else \App\histories::insert(['user_id' => $user->id, 'place_id' => $from, 'count' => 1]);
		if ($his2) $his2->update(['count' => $his2->count+1]);
		else \App\histories::insert(['user_id' => $user->id, 'place_id' => $to, 'count' => 1]);

		return response($result, 200);
	}

	public function getTransfer($arr)
	{
		$number = 0;
		$prev = $arr[0]->line;
		foreach ($arr as $key => $value) {
			if ($value->line != $prev) {
				$number++;
			}
			$prev = $value->line;
		}
		return $number;
	}

	public function getTravel($data)
	{
		$depart = $data[0]->departure_time;
		$arrival = collect($data)->last()->arrival_time;

		$a = new \DateTime(date("Y-m-d ".$depart));
		$b = new \DateTime(date("Y-m-d ".$arrival));
		$dif = date_diff($a, $b);

		return str_pad($dif->h, 2, 0, 0).":".str_pad($dif->i, 2, 0, 0).":".str_pad($dif->s, 2, 0, 0);
	}

	public function routing($rd)
	{
		$last = $rd->lastSchedules();

		if ($last->to_place->id == $this->to) {
			$this->queue->insert($rd, $rd->time);

			if ($this->queue->count() > 5) {
				$this->queue->extract();
			}
			return false;
		}

		if ($this->queue->count() >= 5 && $this->queue->top()->time < $rd->time) {
			return false;
		}

		$list = DB::select("
			SELECT * FROM `schedules`
			WHERE (to_place_id, arrival_time, type) IN
			(
				SELECT to_place_id, min(arrival_time), type FROM `schedules`
				WHERE `from_place_id` = ? && `departure_time` >= ? && `status` = 'AVAILABLE' && `to_place_id` NOT IN (".("'".implode("', '", $rd->visits)."'").")
				GROUP BY `to_place_id`, `type`
			)
		", [$last->to_place->id, $rd->time]);

		foreach ($list as $key => $value) {
			$clone = clone $rd;

			$value->from_place = $this->memo[$value->from_place_id];
			$value->to_place = $this->memo[$value->to_place_id];
			unset($value->from_place_id, $value->to_place_id);

			$clone->visits[] = $value->to_place->id;
			$clone->schedules[] = $value;
			$clone->time = $value->arrival_time;

			$this->routing($clone);
		}
	}
}
