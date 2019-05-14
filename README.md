# 길찾기 사이트 (세계대회 문제)

## 개요

경기가 열리는 지역에서 여러가지 스팟이 있고, 스팟에 따른 전철과 버스가 존재합니다.  
전철과 버스의 스케쥴 표를 이용하여, 스팟에서 스팟으로 이동하는 많은 방법 중, 가장 짧은 시간내에 이동(도착)할 수 있는 길을 안내해주는 사이트입니다.  
길찾기 기능말고도 스팟들의 사진, 정보들을 알 수 있는 기능이있습니다.

## 개발

rest-api 방식을 이용하여 클라이언트와 서버간의 통신을 하였습니다.  
서버의 개발언어로는 php, 프레임워크인 laravel을 사용하였습니다.  
클라이언트는 javascript, 라이브러리인 vue를 사용하였고, 또 다른 라이브러리인 axios를 사용하여 서버와 통신했습니다.

## 보실 때

- 2018년 11월쯔음에 작성한 코드입니다.
- 저가 쓴 코드들은 모두 저가 이해한 상태에서 쓰는 것이며, 왜 이렇게 작성하였냐고 묻는다면, 대답할 수 있습니다.
- Server측에서 저가 작성한 부분
	- /laravel/routes
		- [api.php](https://github.com/jong-hui/find-way-project/blob/master/jonghui_Server2_A/laravel/routes/api.php)
		- [web.php](https://github.com/jong-hui/find-way-project/blob/master/jonghui_Server2_A/laravel/routes/web.php)
	- /laravel/app
		- [users.php](https://github.com/jong-hui/find-way-project/blob/master/jonghui_Server2_A/laravel/app/users.php)
		- [histories.php](https://github.com/jong-hui/find-way-project/blob/master/jonghui_Server2_A/laravel/app/histories.php)
		- [places.php](https://github.com/jong-hui/find-way-project/blob/master/jonghui_Server2_A/laravel/app/places.php)
		- [schedules.php](https://github.com/jong-hui/find-way-project/blob/master/jonghui_Server2_A/laravel/app/schedules.php)
	- /laravel/app/Http/Controllers
		- [placesController.php](https://github.com/jong-hui/find-way-project/blob/master/jonghui_Server2_A/laravel/app/Http/Controllers/placesController.php)
		- [routeController.php](https://github.com/jong-hui/find-way-project/blob/master/jonghui_Server2_A/laravel/app/Http/Controllers/routeController.php)
		- [schedulesController.php](https://github.com/jong-hui/find-way-project/blob/master/jonghui_Server2_A/laravel/app/Http/Controllers/schedulesController.php)
		- [usersController.php](https://github.com/jong-hui/find-way-project/blob/master/jonghui_Server2_A/laravel/app/Http/Controllers/usersController.php)
	- [/laravel/database/migrations/2018_11_09_004712_table_init.php](https://github.com/jong-hui/find-way-project/blob/master/jonghui_Server2_A/laravel/database/migrations/2018_11_09_004712_table_init.php)
	- [/laravel/app/Http/Kernel.php](https://github.com/jong-hui/find-way-project/blob/master/jonghui_Server2_A/laravel/app/Http/Kernel.php)
	- [...](https://github.com/jong-hui/find-way-project/tree/master/jonghui_Server2_A/laravel)
- Client측에서 저가 작성한 부분
	- [/assets/js/script.js](https://github.com/jong-hui/find-way-project/tree/master/jonghui_Server2_A/laravel)
	- [index.html](https://github.com/jong-hui/find-way-project/blob/master/jonghui_Server2_B/index.html)