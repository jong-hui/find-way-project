dd = console.log;
token = username = role = "";
api = null;

function load () {
	token = localStorage['token'];
	username = localStorage['username'];
	role = localStorage['role'];
}

function axiosRefresh () {
	api = axios.create({
		baseURL : "http://localhost/jonghui_Server2_A/api/v1",
		headers : {
			Authorization : "Basic "+btoa("jonghui:1234")
		},
		params : {
			token : localStorage['token']
		}
	});
}

load();
axiosRefresh();



$(function() {

	const App = new Vue({
		el : "#app",

		data () {
			return {
				page : 'home',

				login : !!token,
				username : username,
				password : "",
				role : role,

				modal : false,
				to_focus : false,
				from_focus : false,

				places : [],

				departure : "",
				from : "",
				to : "",
				from_places : [],
				to_places : [],

				result : [],

				floating : null,

				route: [],

				colors : {},

				create : { name : '', latitude : '', longitude : '', description : '', x : '', y : ''},
				update : { id : '', name : '', latitude : '', longitude : '', description : '', x : '', y : ''},
			}
		},

		created () {
			this.getPlace();

			$(document).on('mousedown', () => {
				this.floating = null;
			});
		},

		methods : {
			getPlace () {
				api
					.get("place")
					.then((e) => {
						this.places = e.data;
					}).catch(this.feed);
			},

			randColor () {
				return "#"+Math.random().toString(16).substring(2, 8);
			},

			color (line) {
				if (this.colors[line] == undefined) {
					this.colors[line] = this.randColor();
				}

				return this.colors[line];
			},

			floatingOpen (id) {
				api
					.get("place/"+id)
					.then((e) => {
						this.floating = e.data;
					}).catch(this.feed);
			},

			routeSearch () {
				var from_id = this.places.find(x => x.name == this.from);
				var to_id = this.places.find(x => x.name == this.to);

				if (from_id == undefined || to_id == undefined) return false;

				from_id = from_id.id;
				to_id = to_id.id;

				api
					.get(`/route/search/${from_id}/${to_id}/${(this.departure == "" ? "/" : this.departure)}`)
					.then((e) => {
						this.result = e.data;
						this.page = 'home';
					}).catch(this.feed);
			},

			createForm() {
				var form = $("#create")[0];
				var formData = new FormData(form);

				api
					.post("place", formData)
					.then((e) => {
						alert(e.data.message);
						this.getPlace();
						this.create = { name : '', latitude : '', longitude : '', description : '', x : '', y : ''}
					}).catch(this.feed);
			},

			updateForm () {
				var form = $("#update")[0];
				var formData = new FormData(form);

				api
					.post("place/"+$("#updateId").val(), formData)
					.then((e) => {
						alert(e.data.message);
						this.getPlace();
						this.update = { id : '', name : '', latitude : '', longitude : '', description : '', x : '', y : ''},
						$("#update input[type=file]").val('');
						$("#updateId").val("");
					}).catch(this.feed);
			},

			deleteForm () {
				var form = $("#delete")[0];
				var formData = new FormData(form);

				api
					.post("place/"+$("#deleteId").val(), formData)
					.then((e) => {
						alert(e.data.message);
						this.getPlace();
						$("#deleteId").val("");
					}).catch(this.feed);
			},

			updateSet (e) {
				api
					.get("place/"+$("#updateId").val())
					.then((e) => {
						dd(e.data);
						this.update = e.data;
					}).catch(this.feed);
			},

			logout () {
				api
					.get("auth/logout")
					.then((e) => {
						username = localStorage['username'] = this.username = role = localStorage['role'] = this.role = token = localStorage['token'] = this.token = "";

						this.login = false;
						
						load();
						axiosRefresh();
						alert("logout success");
					}).catch(this.feed);
			},

			userLogin () {
				api
					.post("auth/login", {
						username : this.username,
						password : this.password
					}).then((e) => {
						token = localStorage['token'] = this.token = e.data.token;
						role = localStorage['role'] = this.role = e.data.role;
						username = localStorage['username'] = this.username;

						this.login = true;
						this.modal = false;
						this.password = "";
						this.page = "home";

						load();
						axiosRefresh();
						alert("login success");
					}).catch(this.feed);
			},

			feed (e) {
				if (e.response.data.message || e.response.data.Message) {
					alert(e.response.data.message);
				}
			},
		},

		watch : {
			from (e) {
				this.from_places = e == "" ? [] : this.places.filter(x => ~x.name.toLowerCase().indexOf(e));
			},
			to (e) {
				this.to_places = e == "" ? [] : this.places.filter(x => ~x.name.toLowerCase().indexOf(e));
			}
		}
	});
	
})