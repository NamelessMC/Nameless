(function(){
	"use strict";
	
	var root = this,
		Chart = root.Chart,
		helpers = Chart.helpers;

	var defaultConfig = {

		///Boolean - Whether grid lines are shown across the chart
		scaleShowGridLines : true,

		//String - Colour of the grid lines
		scaleGridLineColor : "rgba(0,0,0,.05)",

		//Number - Width of the grid lines
		scaleGridLineWidth : 1,

		//Boolean - Whether the line is curved between points
		bezierCurve : true,

		//Number - Tension of the bezier curve between points
		bezierCurveTension : 0.4,

		//Boolean - Whether to show a dot for each point
		pointDot : true,

		//Number - Radius of each point dot in pixels
		pointDotRadius : 4,

		//Number - Pixel width of point dot stroke
		pointDotStrokeWidth : 1,

		//Number - amount extra to add to the radius to cater for hit detection outside the drawn point
		pointHitDetectionRadius : 20,

		//Boolean - Whether to show a stroke for datasets
		datasetStroke : true,

		//Number - Pixel width of dataset stroke
		datasetStrokeWidth : 2,

		//Boolean - Whether to fill the dataset with a colour
		datasetFill : true,
		
		//Number - the scale that will be used as the main scale.
		mainScale : 0,

		//Number[] - what scales should be drawn
		drawScale: [0],

		//Number[] - what scales get their horizontal storkes drawn
		drawScaleStroke: [0],
		
		//String - A legend template
		legendTemplate : "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].strokeColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>"

	};
	


	Chart.Type.extend({
		name: "MultiAxisLine",
		defaults : defaultConfig,
		initialize:  function(data){
			
			//Declare the extension of the default point, to cater for the options passed in to the constructor
			this.PointClass = Chart.Point.extend({
				strokeWidth : this.options.pointDotStrokeWidth,
				radius : this.options.pointDotRadius,
				display: this.options.pointDot,
				hitDetectionRadius : this.options.pointHitDetectionRadius,
				ctx : this.chart.ctx,
				inRange : function(mouseX){
					return (Math.pow(mouseX-this.x, 2) < Math.pow(this.radius + this.hitDetectionRadius,2));
				}
			});
			

			this.ScaleClass = Chart.Scale.extend({
				//overwrite fit from chart.scale to add extra padding
				fit: function(){
					//@TODO direct copy of parent fit.
					//need to extend, but I don't know how.
					this.startPoint = (this.display) ? this.fontSize : 0;
					this.endPoint = (this.display) ? this.height - (this.fontSize * 1.5) - 5 : this.height; // -5 to pad labels
					this.startPoint += this.padding;
					this.endPoint -= this.padding;
					var cachedHeight = this.endPoint - this.startPoint,
						cachedYLabelWidth;
					this.calculateYRange(cachedHeight);
					this.buildYLabels();
					this.calculateXLabelRotation();
					while((cachedHeight > this.endPoint - this.startPoint)){
						cachedHeight = this.endPoint - this.startPoint;
						cachedYLabelWidth = this.yLabelWidth;
						this.calculateYRange(cachedHeight);
						this.buildYLabels();
						if (cachedYLabelWidth < this.yLabelWidth){
							this.calculateXLabelRotation();
						}
					}
					
					
					//only local change to fit function additions:
					this.xScalePaddingLeft += (this.totalScales -1 ) * 40; //@TODO fix magic '40'

				},
				drawMulti : function(){
					if(this.axis == this.mainScale){
						this.draw();
						return;
					}else if (this.drawScale){
						var ctx = this.ctx,
						yLabelGap = (this.endPoint - this.startPoint) / this.steps,
						xStart = Math.round(this.xScalePaddingLeft);
						if (this.display){
							ctx.fillStyle = this.textColor;
							ctx.font = this.font;
							helpers.each(this.yLabels,function(labelString,index){
								var yLabelCenter = this.endPoint - (yLabelGap * index),
									linePositionY = Math.round(yLabelCenter);
	
								ctx.textAlign = "right";
								ctx.textBaseline = "middle";
								if (this.showLabels){
									//@TODO fix magic '40'
									var shift = this.axis * 40;
									ctx.fillText(labelString, xStart - shift, yLabelCenter);
								}
								if(this.drawStroke){
									ctx.beginPath();
									if (index > 0){
										// This is a grid line in the centre, so drop that
										ctx.lineWidth = this.gridLineWidth;
										ctx.strokeStyle = this.gridLineColor;
									} else {
										// This is the first line on the scale
										ctx.lineWidth = this.lineWidth;
										ctx.strokeStyle = this.lineColor;
									}
		
									linePositionY += helpers.aliasPixel(ctx.lineWidth);
		
									ctx.moveTo(xStart, linePositionY);
									ctx.lineTo(this.width, linePositionY);
									ctx.stroke();
									ctx.closePath();
		
									ctx.lineWidth = this.lineWidth;
									ctx.strokeStyle = this.lineColor;
									ctx.beginPath();
									ctx.moveTo(xStart - 5, linePositionY);
									ctx.lineTo(xStart, linePositionY);
									ctx.stroke();
									ctx.closePath();
								}
	
							},this);
						}
					}
					return;
				}
			});
			this.ScaleClass.temp = 'test';
			this.scales = [];
			this.datasets = [];
			
			//Set up tooltip events on the chart 
			if (this.options.showTooltips){
				helpers.bindEvents(this, this.options.tooltipEvents, function(evt){
					var activePoints = (evt.type !== 'mouseout') ? this.getPointsAtEvent(evt) : [];
					this.eachPoints(function(point){
						point.restore(['fillColor', 'strokeColor']);
					});
					helpers.each(activePoints, function(activePoint){
						activePoint.fillColor = activePoint.highlightFill;
						activePoint.strokeColor = activePoint.highlightStroke;
					});
					this.showTooltip(activePoints);
				});
			}

			//Iterate through each of the datasets, and build this into a property of the chart
			helpers.each(data.datasets,function(dataset){

				var datasetObject = {
					axis : dataset.axis,
					label : dataset.label || null,
					fillColor : dataset.fillColor,
					strokeColor : dataset.strokeColor,
					pointColor : dataset.pointColor,
					pointStrokeColor : dataset.pointStrokeColor,
					points : []
				};

				this.datasets.push(datasetObject);				

				helpers.each(dataset.data,function(dataPoint,index){
					//Add a new point for each piece of data, passing any required data to draw.
					datasetObject.points.push(new this.PointClass({
						value : dataPoint,
						label : data.labels[index],
						datasetLabel: dataset.label,
						strokeColor : dataset.pointStrokeColor,
						fillColor : dataset.pointColor,
						highlightFill : dataset.pointHighlightFill || dataset.pointColor,
						highlightStroke : dataset.pointHighlightStroke || dataset.pointStrokeColor,
						axis: dataset.axis
					}));
				},this);

				this.buildScale(data.labels, dataset.axis);

				this.eachPoints(function(point, index){
					helpers.extend(point, {
						x: this.scales[dataset.axis].calculateX(index),
						y: this.scales[dataset.axis].endPoint
					});
					point.save();
				}, this);				
			},this);

			
			this.render();
		},
		update : function(){
			helpers.each(this.scales, function(myScale){
				myScale.update();
			});
			// Reset any highlight colours before updating.
			helpers.each(this.activeElements, function(activeElement){
				activeElement.restore(['fillColor', 'strokeColor']);
			});
			this.eachPoints(function(point){
				point.save();
			});
			this.render();
		},
		eachPoints : function(callback){
			helpers.each(this.datasets,function(dataset){
				helpers.each(dataset.points,callback,this);
			},this);
		},
		getPointsAtEvent : function(e){
			var pointsArray = [],
				eventPosition = helpers.getRelativePosition(e);
			helpers.each(this.datasets,function(dataset){
				helpers.each(dataset.points,function(point){
					if (point.inRange(eventPosition.x,eventPosition.y)) pointsArray.push(point);
				});
			},this);
			return pointsArray;
		},
		buildScale : function(labels, axis){
			var self = this;

			var dataTotal = function(){
				var values = [];
				self.eachPoints(function(point){
					if(point.axis == axis){
						values.push(point.value);
					}
				});

				return values;
			};						
			
			var scaleOptions = {
				mainScale : this.options.mainScale,
				drawScale: this.options.drawScale.indexOf(axis) > -1 ? 1 : 0,
				drawStroke : this.options.drawScaleStroke.indexOf(axis) > -1 ? 1 : 0,
				totalScales : this.options.drawScale.length,
				axis : axis,
				templateString : this.options.scaleLabel,
				height : this.chart.height,
				width : this.chart.width,
				ctx : this.chart.ctx,
				textColor : this.options.scaleFontColor,
				fontSize : this.options.scaleFontSize,
				fontStyle : this.options.scaleFontStyle,
				fontFamily : this.options.scaleFontFamily,
				valuesCount : labels.length,
				beginAtZero : this.options.scaleBeginAtZero,
				integersOnly : this.options.scaleIntegersOnly,
				calculateYRange : function(currentHeight){
					var updatedRanges = helpers.calculateScaleRange(
						dataTotal(),
						currentHeight,
						this.fontSize,
						this.beginAtZero,
						this.integersOnly
					);
					helpers.extend(this, updatedRanges);
				},
				showXLabels: (this.options.showXLabels) ? this.options.showXLabels : true,
				xLabels : labels,
				font : helpers.fontString(this.options.scaleFontSize, this.options.scaleFontStyle, this.options.scaleFontFamily),
				lineWidth : this.options.scaleLineWidth,
				lineColor : this.options.scaleLineColor,
				gridLineWidth : (this.options.scaleShowGridLines) ? this.options.scaleGridLineWidth : 0,
				gridLineColor : (this.options.scaleShowGridLines) ? this.options.scaleGridLineColor : "rgba(0,0,0,0)",
				padding: (this.options.showScale) ? 0 : this.options.pointDotRadius + this.options.pointDotStrokeWidth,
				showLabels : this.options.scaleShowLabels,
				display : this.options.showScale
			};

			if (this.options.scaleOverride){
				helpers.extend(scaleOptions, {
					calculateYRange: helpers.noop,
					steps: this.options.scaleSteps,
					stepValue: this.options.scaleStepWidth,
					min: this.options.scaleStartValue,
					max: this.options.scaleStartValue + (this.options.scaleSteps * this.options.scaleStepWidth)
				});
			}
			this.scales[axis] = new this.ScaleClass(scaleOptions);
		},
		addData : function(valuesArray,label){
			//Map the values array for each of the datasets
			helpers.each(valuesArray,function(value,datasetIndex){
				//Add a new point for each piece of data, passing any required data to draw.
				this.datasets[datasetIndex].points.push(new this.PointClass({
					value : value,
					label : label,
					x: this.scales[datasetIndex].calculateX(this.scales.valuesCount+1),
					y: this.scales[datasetIndex].endPoint,
					strokeColor : this.datasets[datasetIndex].pointStrokeColor,
					fillColor : this.datasets[datasetIndex].pointColor
				}));
			},this);

			this.scales[this.options.mainScale].addXLabel(label);
			//Then re-render the chart.
			this.update();
		},
		removeData : function(){
			this.scales[this.options.mainScale].removeXLabel();
			//Then re-render the chart.
			helpers.each(this.datasets,function(dataset){
				dataset.points.shift();
			},this);
			this.update();
		},
		reflow : function(){
			var newScaleProps = helpers.extend({
				height : this.chart.height,
				width : this.chart.width
			});
			this.scales[this.options.mainScale].update(newScaleProps);
		},
		draw : function(ease){
			var easingDecimal = ease || 1;
			this.clear();

			var ctx = this.chart.ctx;

			// Some helper methods for getting the next/prev points
			var hasValue = function(item){
				return item.value !== null;
			},
			nextPoint = function(point, collection, index){
				return helpers.findNextWhere(collection, hasValue, index) || point;
			},
			previousPoint = function(point, collection, index){
				return helpers.findPreviousWhere(collection, hasValue, index) || point;
			};

			helpers.each(this.scales, function(myScale, index){
					myScale.drawMulti();
			}, this);

			helpers.each(this.datasets,function(dataset){
				var pointsWithValues = helpers.where(dataset.points, hasValue);
				
				//Transition each point first so that the line and point drawing isn't out of sync
				//We can use this extra loop to calculate the control points of this dataset also in this loop

				helpers.each(dataset.points, function(point, index){
					if (point.hasValue()){
						point.transition({
							y : this.scales[dataset.axis].calculateY(point.value),
							x : this.scales[this.options.mainScale].calculateX(index)
						}, easingDecimal);
					}
				},this);


				// Control points need to be calculated in a seperate loop, because we need to know the current x/y of the point
				// This would cause issues when there is no animation, because the y of the next point would be 0, so beziers would be skewed
				if (this.options.bezierCurve){
					helpers.each(pointsWithValues, function(point, index){
						var tension = (index > 0 && index < pointsWithValues.length - 1) ? this.options.bezierCurveTension : 0;
						point.controlPoints = helpers.splineCurve(
							previousPoint(point, pointsWithValues, index),
							point,
							nextPoint(point, pointsWithValues, index),
							tension
						);

						// Prevent the bezier going outside of the bounds of the graph

						// Cap puter bezier handles to the upper/lower scale bounds
						if (point.controlPoints.outer.y > this.scales[dataset.axis].endPoint){
							point.controlPoints.outer.y = this.scales[dataset.axis].endPoint;
						}
						else if (point.controlPoints.outer.y < this.scales[dataset.axis].startPoint){
							point.controlPoints.outer.y = this.scales[dataset.axis].startPoint;
						}

						// Cap inner bezier handles to the upper/lower scale bounds
						if (point.controlPoints.inner.y > this.scales[dataset.axis].endPoint){
							point.controlPoints.inner.y = this.scales[dataset.axis].endPoint;
						}
						else if (point.controlPoints.inner.y < this.scales[dataset.axis].startPoint){
							point.controlPoints.inner.y = this.scales[dataset.axis].startPoint;
						}
					},this);
				}


				//Draw the line between all the points
				ctx.lineWidth = this.options.datasetStrokeWidth;
				ctx.strokeStyle = dataset.strokeColor;
				ctx.beginPath();

				helpers.each(pointsWithValues, function(point, index){
					if (index === 0){
						ctx.moveTo(point.x, point.y);
					}
					else{
						if(this.options.bezierCurve){
							var previous = previousPoint(point, pointsWithValues, index);

							ctx.bezierCurveTo(
								previous.controlPoints.outer.x,
								previous.controlPoints.outer.y,
								point.controlPoints.inner.x,
								point.controlPoints.inner.y,
								point.x,
								point.y
							);
						}
						else{
							ctx.lineTo(point.x,point.y);
						}
					}
				}, this);

				ctx.stroke();

				if (this.options.datasetFill && pointsWithValues.length > 0){
					//Round off the line by going to the base of the chart, back to the start, then fill.
					ctx.lineTo(pointsWithValues[pointsWithValues.length - 1].x, this.scales[dataset.axis].endPoint);
					ctx.lineTo(pointsWithValues[0].x, this.scales[dataset.axis].endPoint);
					ctx.fillStyle = dataset.fillColor;
					ctx.closePath();
					ctx.fill();
				}

				//Now draw the points over the line
				//A little inefficient double looping, but better than the line
				//lagging behind the point positions
				helpers.each(pointsWithValues,function(point){
					point.draw();
				});
				
			},this);

		}
	});


}).call(this);