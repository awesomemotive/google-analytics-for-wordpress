jQuery(
	function () {

		var generate_graph = function (graph_id) {
			var data   = [{x: 0, y: 10}, {x: 1, y: 2}, {x: 2, y: 2}, {x: 3, y: 20}, {x: 4, y: 13}];
			var length = data.length;
			var target = document.getElementById(graph_id);
			var width  = 810;
			var xaxis  = [15,16,1,2,3];

			var graph  = new Rickshaw.Graph({
				element : target.querySelector(".yoast-graph-holder"),
				width   : width,
				height  : 275,
				series  : [{
					name : 'visitors per day',
					color: 'steelblue',
					data : data
				}],
				renderer: 'line'
			});

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

			new Rickshaw.Graph.Axis.X(
				{
					element   : target.querySelector('.yoast-graph-xaxis'),
					graph     : graph,
					grid      : true,
					//tickValues : xaxis
					tickFormat: format,
					orientation: 'bottom',
					pixelsPerTick: width / length
				}
			);



			graph.render();
		}


		jQuery('.yoast-graph').each(
			function (number, element) {
				var graph_id = jQuery(element).attr('id');

				generate_graph(graph_id);

			}
		);


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