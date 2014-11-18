
jQuery.fn.extend (
	{
		yoast_ga_graph : function( ) {

			return this.each(
				function() {
					var element  = jQuery(this);
					var graph_id = jQuery(element).attr('id');			// Getting ID-attribute from element
					var target   = document.getElementById(graph_id);	// Element obtaining doing the W3c way

					// Object for doing the magic
					var graph = {
						data      : [],		// Placeholder for all getted data
						axis      : {		// The values for X and Y axis
							x: [],
							y: []
						},
						width     : 810,	// The width of the graph
						height    : 300,
						graph     : '',		// Graph element
						graph_axis: {		// The axis for X and Y
							x: '',
							y: ''
						},
						graph_hover : [],	// Hover element

						init: function () {
							this.get_data();
							this.add_events();
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
							this.create_hover();
							this.render();
						},


						create_graph: function () {
							this.graph = new Rickshaw.Graph(
								{
									element : target.querySelector(".yoast-graph-holder"),
									width   : this.width,
									height  : this.height,
									series  : [{
										name : element.attr('date-label'),
										color: 'steelblue',
										data : this.data
									}],
									renderer : 'line',
									padding : {
										top    : 0.05,
										left   : 0,
										right  : 0,
										bottom : 0.02
									}
								}
							);
						},

						create_axis: function () {
							var length = this.data.length;

							this.graph_axis.x = new Rickshaw.Graph.Axis.X(
								{
									element      : target.querySelector('.yoast-graph-xaxis'),
									graph        : this.graph,
									grid         : true,
									tickFormat   : graph.format_axis_x,
									orientation  : 'bottom',
									pixelsPerTick: this.width / length
								}
							);

							this.graph_axis.y = new Rickshaw.Graph.Axis.Y(
								{
									element       : target.querySelector('.yoast-graph-yaxis'),
									graph         : this.graph,
									orientation   : 'left',
									pixelsPerTick : this.height / 5,

									// If n is 0 return emptystring, to prevent zero displayed on graph
									tickFormat   : function( n ) {
										return (n === 0) ? '' : n;
									}
								}
							);

						},

						create_hover : function() {
							this.graph_hover = new Rickshaw.Graph.HoverDetail(
								{
									graph     : this.graph,
									formatter : function(series, x, y) {
										var swatch = '<span class="detail_swatch" style="background-color: ' + series.color + '"></span>';
										var content = swatch + series.name + ": " + parseInt(y) + '<br>';
										return content;
									}
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


	}
);