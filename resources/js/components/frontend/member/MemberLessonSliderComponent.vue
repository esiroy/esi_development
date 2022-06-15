<template>
    <div class="custom-editor">

        <div id="editor" class="row my-2" >

            <div class="tool-container">
            
                <div class="tool-wrapper">
                    <div :class="['tool', (isSelector) ? 'active' : '']" @click="activateSelector(1)">
                        <i class="fa fa-mouse-pointer" aria-hidden="true" ></i>
                    </div>

                    <div :class="['tool', (isText) ? 'active' : '']" @click="activateTextEditor">
                      <i class="fa fa-font" aria-hidden="true"></i>
                    </div>
                </div>

                 <div class="tool-wrapper">
                    <div :class="['tool', (isPencil) ? 'active' : '']"  @click="activatePencil(1)">
                        <i class="fa fa-pen" aria-hidden="true" ></i>
                    </div>                    
                    <div :class="['tool', (isBrush) ? 'active' : '']"  @click="activateBrush(1)">
                        <i class="fa fa-paint-brush" aria-hidden="true" ></i>  
                    </div>                        
                 </div>

                 <div class="tool-wrapper">
                    <div :class="['tool', (isLine) ? 'active' : '']"  @click="activateLine(1)">
                        <i class="fa fa-minus" aria-hidden="true"></i>
                    </div>        
                          
                    <div :class="['tool', (isCircle) ? 'active' : '']"  @click="activateCircle(1)">
                        <b-icon icon="circle" font-scale="1"> </b-icon>
                    </div> 
                                                       
                 </div>

                <!--            
                 <div class="tool-wrapper">
                    <div :class="['tool', (isSquare) ? 'active' : '']"  @click="activateSquare(1)">
                        <i class="fa fa-pen" aria-hidden="true" ></i>
                    </div>                    
                    <div :class="['tool', (isSpray) ? 'active' : '']"  @click="activateSpray(1)">
                        <i class="fa fa-paint-brush" aria-hidden="true" ></i>  
                    </div>                        
                 </div>
                 -->


                 <!-- ADDITIONAL OPTIONS -->
                 <div class="tool-wrapper mt-2">
                    <div class="brushes" v-show="isBrush || isLine">
                        <button type="button" value="5" :class="['brush', (stroke == 5) ? 'active' : '']"  @click="setBrushStroke(1, 5)"></button>
                        <button type="button" value="10" :class="['brush', (stroke == 10) ? 'active' : '']"  @click="setBrushStroke(1, 10)"></button>
                        <button type="button" value="15" :class="['brush', (stroke == 15) ? 'active' : '']"  @click="setBrushStroke(1, 15)"></button>
                        <button type="button" value="20" :class="['brush', (stroke == 20) ? 'active' : '']"  @click="setBrushStroke(1, 20)"></button>
                    </div> 
                </div>         

            </div>

            <div class="canvas-container" style="display:inline-block;">
                <div id="editor1">
                    <canvas
                        ref="canvas1"
                        id="canvas1"
                        :width="canvasWidth"
                        :height="canvasHeight"
                        style="border:1px solid #333"                        
                    ></canvas>
                </div>
            </div>

            <div v-show="isBrush || isPencil "> </div>

            <div class="colors-container">
                <div class="colors-wrapper">

                    <div class="color-selected color-shadow ">
                        <div class="color-preview" :style="colorPreviewStyle"></div>
                    </div>

                    <div class="colors mt-2" >
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
            </div>        
        </div>

        <!--Mirror 
        <div id="Mirror">
            <canvas
                ref="canvas2"
                id="canvas2"
                :width="canvasWidth"
                :height="canvasHeight"
                style="border:1px solid #333"
            ></canvas>
        </div>
        -->

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
            slides: 10,

            //brush
            stroke: 5,
            brushColor: 'black',



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
            }

           
        };
    },
    mounted() {
        const ref1 = this.$refs["canvas1"];
        const ref2 = this.$refs["canvas2"];

        this.canvas[1] = new fabric.Canvas(ref1, { selection: true });
        this.canvas[2] = new fabric.Canvas(ref2, { selection: true });

        this.customSelectorBounds(fabric);
        this.mouseClickHandler();
        this.keyPressHandler();
        this.handleBrushColors();

        //default selected      
        this.activatePencil(1);

    },
    methods: {
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
            this.canvas[1].on('mouse:down', (options) => {

                console.log(this.isText);

                if (this.isText == true) 
                {
                    this.mouseX = options.pointer.x;
                    this.mouseY = options.pointer.y; 

                    var selectedObj = this.canvas[1].getActiveObject();
                    
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

            this.canvas[1].isDrawingMode = false;

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
                fill: "blue",
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

            this.canvas[1].add(text);
        },

        handleBrushColors() {                
            let colors = document.getElementsByClassName("colors")[0];

            colors.addEventListener("click", (event) => {
                this.brushColor = event.target.value || "black";

                if (this.isBrush) {
                    this.activateBrush(1)  
                } else if(this.isPencil) {
                    this.activatePencil(1)
                } else if (this.isLine) {
                    this.activateLine(1)
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

            //this.activateBrush(canvasNum);
        },

        activateTextEditor() 
        {           
            this.resetModes();
            this.isText = true;
            this.mouseClickHandler();
        },
        activateSelector(num) {
            this.removeEvents(num);
            this.resetModes();

            this.isSelector = true;           
            this.changeObjectSelection(num, true);

            this.canvas[num].selection = true;

            this.mouseClickHandler();
        },
        activateBrush(num) 
        {
             this.removeEvents(1); 

            this.resetModes();
            this.isBrush = true;

            this.draw(num);
        },        
        activatePencil(num)  
        {
           
            this.removeEvents(1);
            this.resetModes();
            this.isPencil = true;   

            this.draw(num);
        },
        activateLine(num) {

             this.removeEvents(1)   
            this.resetModes();
           
            this.isLine = true;

            this.drawLine();
        },
        activateCircle(num) {

            this.resetModes(1);
            this.removeEvents(1);
            
           
            this.isCircle = true;

            this.drawCircle();                  
        },
        drawCircle() {
            
            this.canvas[1].on('mouse:down', (object) => {
                this.isDrawingCircle = true;

                var pointer = this.canvas[1].getPointer(object.e);

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
                     

                 this.canvas[1].add(this.circle);

            }).on('mouse:move', (object) => {

                if (!this.isDrawingCircle ) return;
                var pointer = this.canvas[1].getPointer(object.e);

                
                this.circle.set({
                    radius: Math.abs(this.origX - pointer.x)
                });

                this.circle.setCoords();
                this.canvas[1].renderAll();                
                              

            }).on('mouse:up', (object) => {
                this.isDrawingCircle = false;
            });

        },        
        drawLine() {
            
            this.canvas[1].on('mouse:down', (object) => {
                this.isDrawingLine = true;
                var pointer = this.canvas[1].getPointer(object.e);
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

                 this.canvas[1].add(this.line);

            }).on('mouse:move', (object) => {

                if (this.isDrawingLine ) {

                    var pointer = this.canvas[1].getPointer(object.e);
                    this.line.set({ x2: pointer.x, y2: pointer.y });
                    this.line.setCoords();

                    this.canvas[1].renderAll();
                }


            }).on('mouse:up', (object) => {
                this.isDrawingLine = false;

                
            });

        },
        draw(num) 
        {
            this.isDrawing = false;
          
            this.canvas[num].freeDrawingBrush.color = this.brushColor;   

            if (this.isPencil) {

                this.canvas[num].freeDrawingBrush.width = 1;
                this.canvas[num].isDrawingMode = true;

              

            } else if (this.isBrush) {
            
                this.canvas[num].freeDrawingBrush.width = this.stroke;  
                this.canvas[num].isDrawingMode = true;
                
                  
                                      
            } else {
                 this.canvas[num].isDrawingMode = false;

                
            }

            this.canvas[num].on('mouse:down', ({e})  => {

                if (this.isBrush || this.isPencil) {
                    this.isDrawing = true;
                } else {
                    this.isDrawing = false;
                }
                
            }).on('mouse:up', ({e}) => {
                this.isDrawing = false;
            
            }).on('mouse:move', (e) => {

                
                if (this.isDrawing) {
                    //const pointer = this.canvas[num].getPointer(e);
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
        
        removeEvents(num) {
            this.canvas[num].isDrawingMode = false;
            this.canvas[num].selection = false;
            this.canvas[num].off('mouse:down');
            this.canvas[num].off('mouse:up');
            this.canvas[num].off('mouse:move');
        },
        changeObjectSelection(num, value) {
            this.canvas[num].forEachObject(function (obj) {
                obj.selectable = value;
            });
            this.canvas[num].renderAll();
        },
        setText() {
            var obj = this.canvas[1].getActiveObject();
            if (obj) {
                if (param == 'color') {
                    obj.setColor(value);
                } else {
                    obj.set(param, value);
                }
                this.canvas[1].renderAll();
            } 
        },
        deleteObj()
        {
            var selectedObj = this.canvas[1].getActiveObject();

            if (selectedObj) 
            {            
                if (selectedObj.type === 'activeSelection') {
                    selectedObj.canvas = this.canvas[1];
                    selectedObj.forEachObject(function(obj) {
                        selectedObj.canvas.remove(obj);
                    });
                } else if(selectedObj !== null ) {
                    this.canvas[1].remove(selectedObj);                    
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


.colors-container {   
    background-color: #c0c0c0;
    margin:0px 5px 0px;
    width: 100%;
}

.colors-wrapper {
    display: inline-block;
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
    padding-top: 2px;
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


button.brush.active {
    background-color: #04127b;
}


.right-block {
    width: 640px;
}

#paint-canvas {
    cursor: crosshair;
}



/*tool*/
.tool-container {

    width: 102px;
    display: inline-block;
    padding: 4px 4px 4px;
    margin: 0px 0px 0px 5px;
    background: #c0c0c0;

}
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

</style>
