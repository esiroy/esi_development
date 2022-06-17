<template>
    <div class="container">

        <div id="editor" class="row my-2">

            <div class="left-container">

                <div class="tool-container">

                    <div class="tool-wrapper">

                        <div :class="['tool', (isSelector) ? 'active' : '']" @click="activateSelector">
                            <i class="fa fa-mouse-pointer" aria-hidden="true" ></i>
                        </div>


                        <div :class="['tool', (isText) ? 'active' : '']" @click="activateTextEditor">
                            <i class="fa fa-font" aria-hidden="true"></i>
                        </div>
            
                        <div :class="['tool', (isPencil) ? 'active' : '']"  @click="activatePencil">
                            <i class="fa fa-pen" aria-hidden="true" ></i>
                        </div>                    
                        <div :class="['tool', (isBrush) ? 'active' : '']"  @click="activateBrush">
                            <i class="fa fa-paint-brush" aria-hidden="true" ></i>  
                        </div>                        
                
                        <div :class="['tool', (isLine) ? 'active' : '']"  @click="activateLine">
                            <i class="fa fa-minus" aria-hidden="true"></i>
                        </div>        
                                
                        <div :class="['tool', (isCircle) ? 'active' : '']"  @click="activateCircle">
                            <b-icon icon="circle" font-scale="1"> </b-icon>
                        </div> 
                    </div>

                   


                    <!-- ADDITIONAL OPTIONS -->
                    <div class="tool-wrapper">

                        <div class="tool">
                            <b-form-input type="color" v-model="brushColor" @change="changeColor" class="color-button"></b-form-input>
                        </div>             
                      
                        <!-- Strokes-->
                        <div class="tool" data-target="#brushStrokes"  data-toggle="collapse"  v-show="isBrush || isLine || isCircle">
                            <b-icon icon="border-width" font-scale="1" style="border:0px"> </b-icon>  
                            <div id="brushStrokes" class="collapse" style="z-index:9999">
                                <div class="brushes-container">
                                    <div class="brushes">
                                        <button type="button" value="5" :class="['tool brush', (stroke == 5) ? 'active' : '']"  @click="setBrushStroke(1, 5)"></button>
                                        <button type="button" value="10" :class="['tool brush', (stroke == 10) ? 'active' : '']"  @click="setBrushStroke(1, 10)"></button>
                                        <button type="button" value="15" :class="['tool brush', (stroke == 15) ? 'active' : '']"  @click="setBrushStroke(1, 15)"></button>
                                        <button type="button" value="25" :class="['tool brush', (stroke == 25) ? 'active' : '']"  @click="setBrushStroke(1, 25)"></button>
                                        <button type="button" value="30" :class="['tool brush', (stroke == 30) ? 'active' : '']"  @click="setBrushStroke(1, 30)"></button>
                                    </div> 
                                </div>
                            </div>
                        </div>                                   



                    </div> 
                </div>

                <div class="canvas-container">
                    <div :id="'editor'+slide" v-for="slide in slides" :key="slide" v-show="slide == currentSlide">                    
                        <canvas
                            :ref="'canvas'+slide"
                            :id="'canvas'+slide"
                            :width="canvasWidth"
                            :height="canvasHeight"
                            style="border:1px solid #ccc;"                        
                        ></canvas>
                    </div>
                </div>

                <div class="info-container">

                     <div class="d-flex justify-content-center">
                        Total Time {{ this.getTime() }} 
                     </div>

                </div>

                <div class="buttons-container mt-3">
                    <div class="d-flex justify-content-center">
                        <div id="prev" class="tool" @click="startSlide">
                            <i class="fa fa-fast-backward" aria-hidden="true"></i>
                        </div>  

                        <div id="prev" class="tool" @click="prevSlide">
                            <i class="fa fa-backward" aria-hidden="true"></i>
                        </div>

                        <div id="prev" class="tool font-weight-strong" style="width:150px">
                            Slide {{ this.currentSlide }} of {{ this.slides }}
                        </div>

                        <div id="next" class="tool" @click="nextSlide">
                            <i class="fa fa-forward" aria-hidden="true"></i>
                        </div>

                        <div id="next" class="tool" @click="lastSlide">
                            <i class="fa fa-fast-forward" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>

            </div>

            <div class="right-container">

                <h2> Teacher's Note </h2>

                <div style="width:375px; border:1px solid #ccc; padding: 5px 5px 5px; margin: 0px 5px 0px">
                    {{ "Text Information here. " }}  {{ "Text Information here. " }} {{ "Text Information here. " }} {{ "Text Information here. " }}
                    {{ "Text Information here. " }}  {{ "Text Information here. " }} {{ "Text Information here. " }} {{ "Text Information here. " }}
                    <br>

                    {{ "Text Information here. " }} <br><br>

                    {{ "Text Information here. " }} <br><br><br>

                    {{ "Text Information here. " }} <br><br><br><br>

                    {{ "Text Information here. " }} <br><br><br><br>

                </div>

                <button>Next Notes </button>
                <button>Previous Notes</button>

            </div>
  
        </div>

        <b-modal
            id="modalAddInputText"
            ref="modalAddInputText"
            title="Add Text"
            @show="resetInputTextModal"
            @hidden="resetInputTextModal"
            @ok="addInputText">
            <form ref="form" @submit.stop.prevent="handleSubmit"> 
                <b-form-group label="Text" label-for="textInput" invalid-feedback="Text is required">
                    <b-form-input id="textInput" v-model="inputText" required></b-form-input>
                </b-form-group>
            </form>
        </b-modal>


    </div>
</template>

<script>
import { fabric } from "fabric";

export default {
    name: "Editor",
    props: {
        canvasWidth: {
            type: [String, Number],
            required: true
        },
        canvasHeight: {
            type: [String, Number],
            required: true
        },
        editorId: {
            type: String,
            default: "c",
            required: false
        }
    },
    data() {
        return {
            canvas: [],

            currentSlide: 1,
            slides: 5,

            //Modes
            isSelector: false,
            isText: false,
            isBrush: false, 
            isPencil: false,
            isCircle: false,

            //Action
            isDrawing: false,
            isDrawingLine:false,            
            isDrawingCircle:false, 

            //Line
            isLine: false,
            Line: null,
            
            history: [],

            currentSlide: 1,
           

            //brush
            stroke: 5,
            brushColor: '#000000',



            //input text
           
            inputText: "",

            //mouseX
            mouseX: 0,
            mouseY: 0,

            //original set mouseX
            originX: 0,
            originY: 0,


            colorPreviewStyle:{
                backgroundColor: "#000" 
            },

            myIntervalTimer: null,
            timerSpeed: 1000,
            timer: 0,
           
        };
    },
    mounted() {
     
        for (var i = 1; i <= this.slides; i++) {
            this.canvas[i]  = new fabric.Canvas('canvas'+i, {
                backgroundColor : "#fff",
            });
        }

        this.customSelectorBounds(fabric);
        this.mouseClickHandler();
        this.keyPressHandler();
       // this.handleBrushColors();

        //default selected      
        this.activatePencil();
        this.startTimer();
    },
    methods: {
        getTime() {
            return this.secondsToHms(this.timer);
        },
        startTimer() {
            this.myIntervalTimer = setInterval(this.updateTimer, this.timerSpeed);
        },
        stopTimer() {
            clearInterval(this.myIntervalTimer); 
        },
        secondsToHms(d) {
            d = Number(d);
            var h = Math.floor(d / 3600);
            var m = Math.floor(d % 3600 / 60);
            var s = Math.floor(d % 3600 % 60);
            var hDisplay = h > 9 ? h+":" : "0"+h+":";
            var mDisplay = m > 9 ? m+":" : "0"+m+":";
            var sDisplay = s > 9 ? s : "0"+s;
            return hDisplay + mDisplay + sDisplay; 
        },        
        updateTimer() {
            this.timer++;
        },
        changeColor() {
            this.setPreviewColor( this.brushColor )
            this.autoSelectTool();
        },
        startSlide() {
            if (this.currentSlide > 1) {
                this.currentSlide = 1;
                this.autoSelectTool();
            }        
        },
        lastSlide() {
            this.currentSlide = this.slides;
            this.autoSelectTool();
        },
        prevSlide() {
            if (this.currentSlide > 1) {
                this.currentSlide--;
                this.autoSelectTool();
            }
        },
        nextSlide() {

            if (this.currentSlide < this.slides) {
                this.currentSlide ++;
                this.autoSelectTool(); 
            } 
        },
        autoSelectTool() {
            if (this.isSelector) this.activateSelector();
            else if (this.isPencil) this.activatePencil();
            else if (this.isBrush) this.activateBrush();
            else if (this.isLine) this.activateLine();
            else if (this.isText) this.activateTextEditor();
            else if (this.isCircle) this.activateCircle();
        },
        customSelectorBounds(fabric) {
            fabric.Object.prototype.transparentCorners = false;
            fabric.Object.prototype.cornerColor = 'blue';
            fabric.Object.prototype.cornerStyle = 'circle';
        },
        keyPressHandler(e) {
            window.onkeydown = (event) => {
                if (event.key === "Delete") {
                    this.deleteObj();
                    return false;
                };                   
            };
        },
        mouseClickHandler() 
        {
            this.canvas[this.currentSlide].on('mouse:down', (options) => {

             

                if (this.isText == true) 
                {
                    this.mouseX = options.pointer.x;
                    this.mouseY = options.pointer.y; 

                    var selectedObj = this.canvas[this.currentSlide].getActiveObject();
                    
                    if (!selectedObj) 
                    {  
                        this.$bvModal.show('modalAddInputText');
                    } else {

                        this.resetModes();                    
                        this.isSelector = true;
                    }
                    
                }                
            });
        },   

        resetModes()  {

            this.canvas[this.currentSlide].isDrawingMode = false;

            this.isDrawingLine      = false;
            this.isDrawing          = false;
            this.isDrawingCircle    = false;
            

            //modes
            this.isSelector     = false
            this.isText         = false;
            this.isBrush        = false;
            this.isPencil       = false;
            this.isLine         = false;
            this.isCircle       = false;
        },
        addInputText() 
        {
            let id = (new Date()).getTime().toString().substr(5);
            let text = new fabric.IText(this.inputText, {
                id: id,
                objecttype: 'text',
                left: this.mouseX,
                top: this.mouseY,
                            
                fontFamily: 'Times New Roman',
                fill: this.brushColor,
                fontSize: '40',
                fontWeight: "bold",
                fontStyle: 'normal',

                //stroke: "red",
                //textBackgroundColor: "green",
                //strokeWidth: "1",                
                //"underline: false
                //"overline: false
                //"linethrough: false"
                //deltaY: false
            });

            this.canvas[this.currentSlide].add(text);
        },

        handleBrushColors() {                
            let colors = document.getElementsByClassName("colors")[0];

            colors.addEventListener("click", (event) => {
                this.brushColor = event.target.value || "#000000";

                if (this.isBrush) {
                    this.activateBrush()  
                } else if(this.isPencil) {
                    this.activatePencil()
                } else if (this.isLine) {
                    this.activateLine()
                } 

                //set Preview
                this.setPreviewColor( this.brushColor )                  
            });
        },
        setPreviewColor(color) {
            this.colorPreviewStyle.backgroundColor = color;
        },
        setBrushColor(canvasNum, color) {
            this.brushColor = color;
            this.activateBrush(canvasNum);
        },
        setBrushStroke(canvasNum, stroke) {
            this.stroke = stroke;

            if (this.isBrush) {
                this.activateBrush(canvasNum);
            }
            
        },

        activateTextEditor() 
        {
            this.removeEvents(); 
            this.resetModes();
            this.canvas[this.currentSlide].defaultCursor = 'text';
            this.isText = true;
            this.mouseClickHandler();
        },
        activateSelector() {
        
            this.removeEvents();
            this.resetModes();
            this.enableSelect();
            this.changeObjectSelection(true);
            this.mouseClickHandler();

            this.isSelector = true;           
            this.canvas[this.currentSlide].selection = true;
        },
        activateBrush() 
        {
            this.removeEvents(); 
            this.resetModes();
            this.isBrush = true;
            this.draw();
        },        
        activatePencil()  
        {           
            this.removeEvents();
            this.resetModes();
            this.isPencil = true; 
            this.draw();
        },
        activateLine() {

            this.resetModes();
            this.removeEvents();   
            this.disableSelect();
            this.isLine = true;
            this.drawLine();
        },
        activateCircle() {
            this.resetModes();
            this.removeEvents();
            this.disableSelect();
            this.isCircle = true;
            this.drawCircle();                  
        },
        drawCircle() {
            
            this.canvas[this.currentSlide].on('mouse:down', (object) => {

                this.isDrawingCircle = true;
                this.disableSelect();

                var pointer = this.canvas[this.currentSlide].getPointer(object.e);

                this.origX = pointer.x;
                this.origY = pointer.y;

                this.circle = new fabric.Circle({
                    left: pointer.x,
                    top: pointer.y,
                    radius: 1,
                    strokeWidth: this.stroke,
                    fill: 'transparent',
                    stroke: this.brushColor,
                    selectable: true,
                   // originX: 'center',
                   // originY: 'center'
                });
                     

                 this.canvas[this.currentSlide].add(this.circle);

            }).on('mouse:move', (object) => {

                if (this.isDrawingCircle ) {
                    this.disableSelect();   
                    var pointer = this.canvas[this.currentSlide].getPointer(object.e);
                    this.circle.set({
                        radius: Math.abs(this.origX - pointer.x)
                    });
                    this.circle.setCoords();
                    this.canvas[this.currentSlide].renderAll(); 
                }         

            }).on('mouse:up', (object) => {

                this.disableSelect();       
                this.isDrawingCircle = false;
            });

        },        
        drawLine() {           

            this.canvas[this.currentSlide].on('mouse:down', (object) => {
                this.isDrawingLine = true;
                this.disableSelect();

                var pointer = this.canvas[this.currentSlide].getPointer(object.e);
                var points = [ pointer.x, pointer.y, pointer.x, pointer.y ];

                this.line = new fabric.Line(points, {
                    strokeWidth: this.stroke,
                    //strokeDashArray: [15, 5],
                    fill: this.brushColor,
                    stroke: this.brushColor,
                    originX: 'center',
                    originY: 'center',
                    selectable: true,
                });
                this.canvas[this.currentSlide].add(this.line);

            }).on('mouse:move', (object) => {
                if (this.isDrawingLine ) {
                    this.disableSelect();
                    var pointer = this.canvas[this.currentSlide].getPointer(object.e);
                    this.line.set({ x2: pointer.x, y2: pointer.y });
                    this.line.setCoords();
                    this.canvas[this.currentSlide].renderAll();
                }
            }).on('mouse:up', (object) => {
                this.disableSelect();
                this.isDrawingLine = false;
            });

        },
        draw() 
        {
            this.isDrawing = false;          
            this.canvas[this.currentSlide].freeDrawingBrush.color = this.brushColor;   

            if (this.isPencil) {
                this.canvas[this.currentSlide].freeDrawingBrush.width = 1;
                this.canvas[this.currentSlide].isDrawingMode = true;

            } else if (this.isBrush) {
                this.canvas[this.currentSlide].freeDrawingBrush.width = this.stroke;  
                this.canvas[this.currentSlide].isDrawingMode = true;

            } else {
                 this.canvas[this.currentSlide].isDrawingMode = false;
            }

            this.canvas[this.currentSlide].on('mouse:down', ({e})  => {

                if (this.isBrush || this.isPencil) {
                    this.isDrawing = true;
                } else {
                    this.isDrawing = false;
                }
                
            }).on('mouse:up', ({e}) => {
                this.isDrawing = false;
            
            }).on('mouse:move', (e) => {
                if (this.isDrawing) {
                    //const pointer = this.canvas[this.currentSlide].getPointer(e);
                    //this.drawRealTime(e, pointer);
                }
            });            

        },
        resetInputTextModal() {
            this.inputText = "";
        },
        drawRealTime(e, pointer) {
            this.canvas2.freeDrawingBrush.onMouseMove(pointer);
        },        
        removeEvents() {

            this.canvas[this.currentSlide].isDrawingMode = false;
            this.canvas[this.currentSlide].selection = false;
            this.canvas[this.currentSlide].off('mouse:down');
            this.canvas[this.currentSlide].off('mouse:up');
            this.canvas[this.currentSlide].off('mouse:move');
        },
        changeObjectSelection(value) {
            this.canvas[this.currentSlide].forEachObject(function (obj) {
                obj.selectable = value;
            });
            this.canvas[this.currentSlide].renderAll();
        },
        enableSelect() {

            this.canvas[this.currentSlide].defaultCursor = 'default';
        
            this.canvas[this.currentSlide].forEachObject(function (obj) {
                obj.selectable = true;
                obj.evented = true;
            });
            this.canvas[this.currentSlide].renderAll();        
        },
        disableSelect() 
        {
            this.canvas[this.currentSlide].defaultCursor = 'crosshair';
            
            this.canvas[this.currentSlide].forEachObject(function (obj) {
                obj.selectable = false;
                obj.evented = false;
            });

            this.canvas[this.currentSlide].renderAll();            
        },
        setText() {
            var obj = this.canvas[this.currentSlide].getActiveObject();
            if (obj) {
                if (param == 'color') {
                    obj.setColor(value);
                } else {
                    obj.set(param, value);
                }
                this.canvas[this.currentSlide].renderAll();
            } 
        },
        deleteObj()
        {
            var selectedObj = this.canvas[this.currentSlide].getActiveObject();

            if (selectedObj) 
            {            
                if (selectedObj.type === 'activeSelection') {
                    selectedObj.canvas = this.canvas[this.currentSlide];
                    selectedObj.forEachObject(function(obj) {
                        selectedObj.canvas.remove(obj);
                    });
                } else if(selectedObj !== null ) {
                    this.canvas[this.currentSlide].remove(selectedObj);                    
                }                
            }
            this.mouseClickHandler();
        }

    }
};
</script>
<style>
.upper-canvas {
    z-index: 1;
    
}

.tools {
    display: flex;
    height: 200px; 
    width: 50px;    
}

.tool {
    width:50%; 
    border:1px solid #000;
    text-align:center;
    margin: 0;
    padding-right: 4px;
    width: 25px;
    height: 25px;
    border: 0;
    border-right: 1px solid black;
    border-bottom: 1px solid black;
    background: transparent;
    outline: 0;
}

.tool-wrapper {
    display: flow-root;
    width: 100%;
}

.left-container {
    background-color: #ececec;
    border-radius: 15px;
    margin-right: 10px;
    padding: 20px 15px 20px;
}

.right-container {
    background-color: #ececec;
    border-radius: 15px;
    margin-right: 10px;
    padding: 20px 15px 20px;
}

/*tool*/
.tool-container {
    width: 100%;
    display: inline-block;
    margin: 0px ;}

.tool {
    float: left;
    width: 47px;
    height: 25px;
    
    top: 0px;
    left: 0px;
    right: 0px;
    bottom: 0px;
    border-top: 1px solid white;
    border-left: 1px solid white;
    border-right: 1px solid #7b7b7b;
    border-bottom: 1px solid #7b7b7b;    
}

.tool.active {
    background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAIAAAACAQMAAABIeJ9nAAAABlBMVEW9vb3///8EwsWUAAAADElEQVQI12NoYHAAAAHEAMFJRSpJAAAAAElFTkSuQmCC) repeat;
    top: 0px;
    left: 0px;
    right: 0px;
    bottom: 0px;
    border-top: 1px solid #7b7b7b;
    border-left: 1px solid #7b7b7b;
    border-right: 1px solid #bdbdbd;
    border-bottom: 1px solid #bdbdbd;   
}



.colors-container {   
    background-color: #c0c0c0;
    margin:0px 5px 0px;
    width: 100%;
}

.colors-wrapper {
    display: inline-block;
}

.color-button {

    width: 100% !important;
    height: 100% !important;
    padding: 0px !important;
    margin: 0px;
    border: none;
    background-color: transparent;
    text-align: center;
    margin: auto;
}

.color-selected {    
    background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAIAAAACAQMAAABIeJ9nAAAABlBMVEW9vb3///8EwsWUAAAADElEQVQI12NoYHAAAAHEAMFJRSpJAAAAAElFTkSuQmCC) repeat;
    border:1px solid #ccc;
    width: 45px;
    height: 45px;
    display: inline-block;
    vertical-align: text-bottom;
    margin: 0px 0px 0px 5px;
}

.color-shadow {
    border-top: 1px solid #7B7B7B;
    border-left: 1px solid #7B7B7B;
    border-right: 1px solid #BBBBBB;
    border-bottom: 1px solid #BBBBBB;
    box-shadow: 1px 1px 0px black inset;
}

.colors {
    display: inline-block;
    text-align: center;
    width: 370px;    
}

.color-preview {
    width: 15px;
    height: 15px;    
    text-align: center;
    margin: 13px;
    box-sizing: border-box;
    -moz-box-sizing: border-box;
    border-top: 2px solid #fff;
    border-left: 2px solid #fff;
    border-right: 2px solid #7B7B7B;
    border-bottom: 2px solid #7B7B7B;    
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

.tool .collapsing {
     -webkit-transition: none;
     transition: none;
}

.brushes-container {

    background-color: #ececec;
    
    width: 235px;
    float: left;
    /* width: 47px; */
    height: 36px;
    /* top: 0px; */
    /* left: 0px; */
    /* right: 0px; */
    /* bottom: 0px; */
    border-top: 1px solid white;
    border-left: 1px solid white;
    border-right: 1px solid #7b7b7b;
    border-bottom: 1px solid #7b7b7b;    

    position: relative;
    z-index: 99999;

}

.brushes {
    padding-top: 2px;
}

.brushes button {
    display: inline-block;
    width: 20%;
    border: 0;
    border-radius: 0;
    background-color: #ece8e8;
    margin-bottom: 5px;
    padding: 5px;
    height: 30px;
    outline: none;
    position: relative;
    cursor: pointer;
    vertical-align: top;
}

.brushes button:after {
    height: 1px;
    display: block;
    background: #808080;
    content: "";
}

.brushes button:nth-of-type(1):after {
    height: 3px;
}

.brushes button:nth-of-type(2):after {
    height: 5px;
}

.brushes button:nth-of-type(3):after {
    height: 10px;
}

.brushes button:nth-of-type(4):after {
    height: 15px;
}

.brushes button:nth-of-type(5):after {
    height: 20px;
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


button.brush.active {
    background-color: #04127b;
}


.right-block {
    width: 640px;
}

#paint-canvas {
    cursor: crosshair;
}




</style>
