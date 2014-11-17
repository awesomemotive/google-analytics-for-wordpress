jQuery.fn.extend (
	{
		yoast_ga_graph : function( ) {

			var registry = {};

			return this.each(
				function() {
					var element  = jQuery(this);
					var graph_id = jQuery(element).attr('id');
					var target   = document.getElementById(graph_id);

					var graph = {
						data      : [],
						axis      : {
							x: [],
							y: []
						},
						width     : 810,
						graph     : '',
						graph_axis: '',

						init: function () {
							this.get_data();
						},

						add_events : function() {
							_this = this;
							jQuery(element).on("graph_update", function (event, response) {
								_this.update(response, _this);
							});
						},

						get_data: function () {

							var data = {
								action     : 'yoast_dashboard_graphdata',
								_ajax_nonce: yoast_ga_dashboard_nonce,
								graph_id   : graph_id
							}

							jQuery.getJSON(ajaxurl, data, this.parse_response);
						},

						parse_response: function (response) {
							graph.set_data(response.data);
							graph.set_x_axis_mapping(response.mapping);

							graph.create();

							graph.add_events();
						},

						set_data: function (data) {
							this.data = data;
						},

						add_data : function( data_to_add ) {
							graph.data.push( data_to_add );
						},

						set_x_axis_mapping: function (mapping) {
							graph.axis.x = mapping;
						},

						add_x_axis_mapping: function( mapping_to_add ) {
							this.axis.x.push( mapping_to_add );
						},

						create: function () {
							this.create_graph();
							this.create_axis();
							this.render();
						},

						create_graph: function () {
							this.graph = new Rickshaw.Graph({
								element : target.querySelector(".yoast-graph-holder"),
								width   : this.width,
								height  : 275,
								series  : [{
									name : 'visitors per day',
									color: 'steelblue',
									data : this.data
								}],
								renderer: 'line'
							});


						},

						create_axis: function () {
							var length = this.data.length;

							this.graph_axis = new Rickshaw.Graph.Axis.X(
								{
									element      : target.querySelector('.yoast-graph-xaxis'),
									graph        : this.graph,
									grid         : true,
									tickFormat   : graph.format_axis_x,
									orientation  : 'bottom',
									pixelsPerTick: this.width / length
								}
							);
						},

						render: function () {
							this.graph.render();
						},

						format_axis_x: function (number) {
							return graph.axis.x[number];
						},

						update: function (response, _this) {
							_this.add_data(response.data);
							_this.add_x_axis_mapping(response.mapping);

							_this.graph.update();
							_this.render();
						}
					}

					graph.init();
				}
			);

		},

		yoast_ga_graph_update : function(response) {
			jQuery( this ).trigger( "graph_update", [response] );
		}
	}

);


jQuery(
	function () {

		jQuery('.yoast-graph').yoast_ga_graph();

		/*
		setTimeout(
			function() {
				jQuery('#graph-visitors').yoast_ga_graph_update(
					{
						data   : {x: 30, y: 10},
						mapping: ['andy']
					}
				);
			},
			500
		);
		*/

		/*
		var generate_graph = function (graph_id) {
			var data   = [{x: 0, y: 10}, {x: 1, y: 2}, {x: 2, y: 2}, {x: 3, y: 20}, {x: 4, y: 13}];

			var target = document.getElementById(graph_id);
			var width  = 810;
			var xaxis  = [15,16,1,2,3];



			 wp.heartbeat.enqueue(
			 'yoast_dashboard_graphdata',
			 data,
			 true
			 )
			 console.log($this.data);
			 $this.data.push( { x: 30, y: 1 } );
			 graph.graph.update();

			 $this.x_axis.push( ['andy'] );
			 $this.render();



			var format = function (n) {

				var map = {
					0: 13,
					1: 14,
					2: 15,
					3: 1,
					4: 2
				};

				return map[n];


			}

		}
		//jQuery('.yoast-graph').yoast_ga_graph_update(123);

		/*
		jQuery('.yoast-graph').each(
			function (number, element) {
				var graph_id = jQuery(element).attr('id');

				generate_graph(graph_id);

			}
		);
		*/


		//
		//
		//var data = [ { x: 12, y: 2 }, { x: 2, y: 10 }, { x: 3, y: 0 }, { x: 4, y: 0 }, { x: 1, y: 0 } ];
		//
		//var graph = new Rickshaw.Graph( {
		//	element: document.querySelector("#graph-visitors"),
		//	width: 580,
		//	height: 250,
		//	series: [ {
		//		color: 'steelblue',
		//		data: data
		//	} ],
		//	renderer: 'line'
		//} );
		//
		//graph.render();
		//
		//console.log(graph);


	}
);