<template>
    <div id="MemberSliderComponent">
        <div class="row">
            <div class="col-6">

                <canvas
                    id="paint-canvas"
                    width="620"
                    height="420"
                    style="border:1px solid #000"
                    class="mt-2"
                    v-show="this.slide == 1"
                ></canvas>

                <canvas
                    id="paint-canvas-2"
                    width="620"
                    height="420"
                    style="border:1px solid #000"
                    class="mt-2"
                    v-show="this.slide == 2"
                ></canvas>


            </div>
        </div>

        <div class="row">
            <div class="col-6">
                <div class="row">

					<div class="col-1">		
						<div class="text-container mt-2 ">				
							<button type="button" class="btn btn-sm btn-light border border-primary" @click="showTextInput">
							    <b-icon icon="fonts" variant="primary"></b-icon>
							</button>


						</div>
					</div>

                    <div class="col-4">
                        <div class="colors mt-2">
                            <button type="button" value="#0000ff"></button>
                            <button type="button" value="#009fff"></button>
                            <button type="button" value="#0fffff"></button>
                            <button type="button" value="#bfffff"></button>
                            <button type="button" value="#000000"></button>
                            <button type="button" value="#333333"></button>
                            <button type="button" value="#666666"></button>
                            <button type="button" value="#999999"></button>
                            <button type="button" value="#ffcc66"></button>
                            <button type="button" value="#ffcc00"></button>
                            <button type="button" value="#ffff00"></button>
                            <button type="button" value="#ffff99"></button>
                            <button type="button" value="#003300"></button>
                            <button type="button" value="#555000"></button>
                            <button type="button" value="#00ff00"></button>
                            <button type="button" value="#99ff99"></button>
                            <button type="button" value="#f00000"></button>
                            <button type="button" value="#ff6600"></button>
                            <button type="button" value="#ff9933"></button>
                            <button type="button" value="#f5deb3"></button>
                            <button type="button" value="#330000"></button>
                            <button type="button" value="#663300"></button>
                            <button type="button" value="#cc6600"></button>
                            <button type="button" value="#deb887"></button>
                            <button type="button" value="#aa0fff"></button>
                            <button type="button" value="#cc66cc"></button>
                            <button type="button" value="#ff66ff"></button>
                            <button type="button" value="#ff99ff"></button>
                            <button type="button" value="#e8c4e8"></button>
                            <button type="button" value="#ffffff"></button>
                        </div>
                    </div>

                    <div class="col-3">
                        <div class="brushes">
                            <button type="button" value="1"></button>
                            <button type="button" value="2"></button>
                            <button type="button" value="3"></button>
                            <button type="button" value="4"></button>
                            <button type="button" value="5"></button>
                        </div>
                    </div>

                    <div class="col-3">
                        <div class="buttons">
                            <button id="clear" type="button">Clear</button>
                            <button id="save" type="button">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <b-modal
            id="modalAddInputText"
            ref="modalAddInputText"
            title="Add Text"
            @show="resetInputTextModal"
            @hidden="resetInputTextModal"
            @ok="addInputText"
        >
            <form ref="form" @submit.stop.prevent="handleSubmit">
                <b-form-group
                    label="Text"
                    label-for="name-input"
                    invalid-feedback="Text is required"
                 
                >
                    <b-form-input
                    id="name-input"
                    v-model="inputText"
                    required
                    ></b-form-input>
                </b-form-group>
            </form>
        </b-modal>

    </div>
</template>

<script>
import Moment from "moment-timezone";

export default {
    name: "member-slider-component",

    components: {},
    props: {
        tutorinfo: Object,
        memberinfo: Object,
        api_token: String,
        csrf_token: String
    },
    data() {
        return {

            slide: 1,

            inputText: "",

            canvas: "",
           

            ctx: "",
            canvasOffset: "",
            offsetX: "",
            offsetY: "",
            scrollX: "",
            scrollY: "",

            // variables to save last mouse position
            // used to see how far the user dragged the mouse
            // and then move the text by that distance
            startX: "",
            startY: "",

            // an array to hold text objects
            texts: [],

            // this will hold the index of the hit-selected tex,
            selectedText: -1,

            //history
            mouseDrag: false,
            //isMouseDown: false,
            points: [],
                        
        };
    },
    mounted: function() {

        this.draw("paint-canvas");
        this.draw("paint-canvas-2");

        this.loadImage('https://pbs.twimg.com/profile_images/1498641868397191170/6qW2XkuI_400x400.png');

        this.canvas = document.getElementById("paint-canvas");
        this.ctx = this.canvas.getContext("2d");

        
        this.canvasOffset = this.getOffset("#paint-canvas");
     
        this.offsetX = this.canvasOffset.left;
        this.offsetY = this.canvasOffset.top;



    },
    methods: {

        getDrawing() {
        
        },
        getOffset(element) 
        {        
            let elem = document.querySelector(element)
            if (elem) {
                let clientRect = elem.getBoundingClientRect();
                let offset = { 
                    top: clientRect.top + window.scrollY, 
                    left: clientRect.left + window.scrollX, 
                };
                return offset;                
            }
        },
        textHittest(x, y, textIndex) {
            let text = this.texts[textIndex];
            return (x >= text.x && x <= text.x + text.width && y >= text.y - text.height && y <= text.y);
        }, 
        // done dragging
        handleMouseUp(e) {          
            this.selectedText = -1;
            this.mouseDrag = false;
            console.log("done")
            e.preventDefault();
        },
        handleMouseOut(e) 
        {            
            this.selectedText = -1;
            this.mouseDrag = false;
            console.log("done")
            e.preventDefault();
        },
        handleMouseDown(e) {
            e.preventDefault();
            this.startX = parseInt(e.clientX - this.offsetX);
            this.startY = parseInt(e.clientY - this.offsetY);
            // Put your mousedown stuff here
            for (var i = 0; i < this.texts.length; i++) {
                if (this.textHittest(this.startX, this.startY, i)) {
                    this.selectedText = i;
                    this.mouseDrag = true;
                } else {
                    //this.mouseDrag = false;
                }
            }
        },

        handleMouseMove(e) {
            if (this.selectedText < 0) {
                return;
            }
            e.preventDefault();
            
            this.mouseX = parseInt(e.clientX - this.offsetX);
            this.mouseY = parseInt(e.clientY - this.offsetY);

            // Put your mousemove stuff here
            var dx = this.mouseX - this.startX;
            var dy = this.mouseY - this.startY;

            this.startX = this.mouseX;
            this.startY = this.mouseY;

            var text = this.texts[this.selectedText];
            text.x += dx;
            text.y += dy;

            this.textDraw();
        },


        resetInputTextModal() {
            this.inputText = "";
        },
		addInputText() 
		{

            // calc the y coordinate for this text on the canvas
            var y = this.texts.length * 20 + 20;

            // get the text from the input element
            var text = {
                text: this.inputText,
                x: 20,
                y: y
            };

            // calc the size of this text for hit-testing purposes
            this.ctx.font = "16px verdana";
            text.width = this.ctx.measureText(text.text).width;
            text.height = 16;

            // put this new text in the texts array
            this.texts.push(text);

            this.textDraw();
           
          
            

		},
        textDraw() 
        {        

            // redraw everything
            this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
            

            for (var i = 0; i < this.texts.length; i++) {
                var text = this.texts[i];
                this.ctx.fillText(text.text, text.x, text.y);
            }

            this.redrawAll();           
        },
        showTextInput() {
            this.box = "";
            this.$bvModal.show('modalAddInputText');
        },		
        loadImage(imageURL) 
        {

            var canvas = document.getElementById('paint-canvas');                       
            let ctx = canvas.getContext("2d");

            canvas.width = 934;
            canvas.height = 622;


            var background = new Image();
            background.src = imageURL;

            background.onload = function(){
                ctx.drawImage(background,0,0);   
            } 

            background.setAttribute('crossorigin', 'anonymous'); // works for me

        },


        redrawAll() {

            if (this.points.length == 0) {
                return;
            }

            //this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);

            for (var i = 0; i < this.points.length; i++) {

                var pt = this.points[i];

                var begin = false;

                if (this.ctx.lineWidth != pt.size) {
                    this.ctx.lineWidth = pt.size;
                    begin = true;
                }
                if (this.ctx.strokeStyle != pt.color) {
                    this.ctx.strokeStyle = pt.color;
                    begin = true;
                }
                if (pt.mode == "begin" || begin) {
                    this.ctx.beginPath();
                    this.ctx.moveTo(pt.x, pt.y);
                }

                this.ctx.lineTo(pt.x, pt.y);

                if (pt.mode == "end" || (i == this.points.length - 1)) {
                    this.ctx.stroke();
                }
            }
            this.ctx.stroke();
        },


        draw(element) {
            // Definitions
            var canvas = document.getElementById(element);
            var context = canvas.getContext("2d");
            var boundings = canvas.getBoundingClientRect();

            // Specifications
            var mouseX = 0;
            var mouseY = 0;
            context.strokeStyle = "black"; // initial brush color
            context.lineWidth = 1; // initial brush width
            var isDrawing = false;

            // Handle Colors
            var colors = document.getElementsByClassName("colors")[0];

            colors.addEventListener("click", function(event) {
                context.strokeStyle = event.target.value || "black";
            });

            // Handle Brushes
            var brushes = document.getElementsByClassName("brushes")[0];

            brushes.addEventListener("click", function(event) {
                context.lineWidth = event.target.value || 1;
            });


            canvas.addEventListener("mousedown", (event) => {
                setMouseCoordinates(event);
                isDrawing = true;

                // Start Drawing
                context.beginPath();
                context.moveTo(mouseX, mouseY);

                //Drag and Drop Text
                this.handleMouseDown(event);
                    
                //History
                this.points.push({
                    x: mouseX,
                    y: mouseY,
                    size: context.lineWidth,
                    color: context.strokeStyle,
                    mode: "begin"
                });

            });            

            // Mouse Move Event
            canvas.addEventListener("mousemove", (event) => {
                setMouseCoordinates(event);

                if (isDrawing) {

                    if (this.mouseDrag == false) 
                    {   
                        context.lineTo(mouseX, mouseY);
                        context.stroke();
                        
                        //History
                        this.points.push({
                            x: mouseX,
                            y: mouseY,
                            size: context.lineWidth,
                            color: context.strokeStyle,
                            mode: "draw"
                        });          
                    } 

                }

                this.handleMouseMove(event);

            });




            // Mouse Up Event
            canvas.addEventListener("mouseup", (event) => {
                setMouseCoordinates(event);
                isDrawing = false;

               

                console.log(this.mouseDrag + "... [end]")

                if (this.mouseDrag == false) 
                {

                    //History
                    this.points.push({
                        x: mouseX,
                        y: mouseY,
                        size: context.lineWidth,
                        color: context.strokeStyle,
                        mode: "end"
                    });        
                }        


                 this.handleMouseUp(event);
            });
    


            // Handle Mouse Coordinates
            function setMouseCoordinates(event) {
                mouseX = event.clientX - boundings.left;
                mouseY = event.clientY - boundings.top;
            }

            // Handle Clear Button
            var clearButton = document.getElementById("clear");

            clearButton.addEventListener("click", () => 
            {
                this.points = [];
                this.texts  = [];
                context.clearRect(0, 0, canvas.width, canvas.height);
             
            });


            this.drawingCanvas = canvas.toDataURL();

            // Handle Save Button
            var saveButton = document.getElementById("save");

            saveButton.addEventListener("click", function() {
                var imageName = prompt("Please enter image name");
                var canvasDataURL = canvas.toDataURL();
                var a = document.createElement("a");
                a.href = canvasDataURL;
                a.download = imageName || "drawing";
                a.click();
            });
        }
    },
    computed: {},
    updated: function() {}
};
</script>

<style type="text/css" scoped>
.colors {
    background-color: #ece8e8;
    text-align: center;
    padding-bottom: 5px;
    padding-top: 10px;
}

.colors button {
    display: inline-block;
    border: 1px solid #00000026;
    border-radius: 0;
    outline: none;
    cursor: pointer;
    width: 20px;
    height: 20px;
    margin-bottom: 5px;
}

.colors button:nth-of-type(1) {
    background-color: #0000ff;
}

.colors button:nth-of-type(2) {
    background-color: #009fff;
}

.colors button:nth-of-type(3) {
    background-color: #0fffff;
}

.colors button:nth-of-type(4) {
    background-color: #bfffff;
}

.colors button:nth-of-type(5) {
    background-color: #000000;
}

.colors button:nth-of-type(6) {
    background-color: #333333;
}

.colors button:nth-of-type(7) {
    background-color: #666666;
}

.colors button:nth-of-type(8) {
    background-color: #999999;
}

.colors button:nth-of-type(9) {
    background-color: #ffcc66;
}

.colors button:nth-of-type(10) {
    background-color: #ffcc00;
}

.colors button:nth-of-type(11) {
    background-color: #ffff00;
}

.colors button:nth-of-type(12) {
    background-color: #ffff99;
}

.colors button:nth-of-type(13) {
    background-color: #003300;
}

.colors button:nth-of-type(14) {
    background-color: #555000;
}

.colors button:nth-of-type(15) {
    background-color: #00ff00;
}

.colors button:nth-of-type(16) {
    background-color: #99ff99;
}

.colors button:nth-of-type(17) {
    background-color: #f00000;
}

.colors button:nth-of-type(18) {
    background-color: #ff6600;
}

.colors button:nth-of-type(19) {
    background-color: #ff9933;
}

.colors button:nth-of-type(20) {
    background-color: #f5deb3;
}

.colors button:nth-of-type(21) {
    background-color: #330000;
}

.colors button:nth-of-type(22) {
    background-color: #663300;
}

.colors button:nth-of-type(23) {
    background-color: #cc6600;
}

.colors button:nth-of-type(24) {
    background-color: #deb887;
}

.colors button:nth-of-type(25) {
    background-color: #aa0fff;
}

.colors button:nth-of-type(26) {
    background-color: #cc66cc;
}

.colors button:nth-of-type(27) {
    background-color: #ff66ff;
}

.colors button:nth-of-type(28) {
    background-color: #ff99ff;
}

.colors button:nth-of-type(29) {
    background-color: #e8c4e8;
}

.colors button:nth-of-type(30) {
    background-color: #ffffff;
}

.brushes {
    padding-top: 5px;
}

.brushes button {
    display: block;
    width: 100%;
    border: 0;
    border-radius: 0;
    background-color: #ece8e8;
    margin-bottom: 5px;
    padding: 5px;
    height: 30px;
    outline: none;
    position: relative;
    cursor: pointer;
}

.brushes button:after {
    height: 1px;
    display: block;
    background: #808080;
    content: "";
}

.brushes button:nth-of-type(1):after {
    height: 1px;
}

.brushes button:nth-of-type(2):after {
    height: 2px;
}

.brushes button:nth-of-type(3):after {
    height: 3px;
}

.brushes button:nth-of-type(4):after {
    height: 4px;
}

.brushes button:nth-of-type(5):after {
    height: 5px;
}

.buttons {
    height: 80px;
    padding-top: 10px;
}

.buttons button {
    display: block;
    width: 100%;
    border: 0;
    border-radius: 0;
    background-color: #ece8e8;
    margin-bottom: 5px;
    padding: 5px;
    height: 30px;
    outline: none;
    position: relative;
    cursor: pointer;
    font-size: 16px;
}

.right-block {
    width: 640px;
}

#paint-canvas {
    cursor: crosshair;
}
</style>
