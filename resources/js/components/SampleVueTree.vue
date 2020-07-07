<template>
	<div>
		<button @click="addNode">Add Node</button>
		<vue-tree-list
			@click="onClick"
			@change-name="onChangeName"
			@delete-node="onDel"
			@add-node="onAddNode"
			@drop="onDrop"
			:model="data"
			default-tree-node-name="new node"
			default-leaf-node-name="new leaf"
			v-bind:default-expanded="false"
		>
			<span class="icon" slot="addTreeNodeIcon">📂</span>
			<span class="icon" slot="addLeafNodeIcon">＋</span>
			<span class="icon" slot="editNodeIcon">📃</span>
			<span class="icon" slot="delNodeIcon">✂️</span>
			<span class="icon" slot="leafNodeIcon">🍃</span>
			<span class="icon" slot="treeNodeIcon">🌲</span>
		</vue-tree-list>
		<button @click="getNewTree">Get new tree</button>
		<pre>
      {{newTree}}
    </pre>
	</div>
</template>
 
<script>
import { VueTreeList, Tree, TreeNode } from "vue-tree-list";
export default {
	components: {
		VueTreeList
	},
	mounted() {
		this.$nextTick(function() {
			let container = document.querySelector(".vtl-node-main");
			let box = document.querySelectorAll(".vtl-tree-node");

			this._addEventListener(container, box);
		});
	},
	data() {
		return {
			parentID: null,
			box: null,
			container: null,
			newTree: {},
			data: new Tree([
				{
					name: "Node 1",
					id: 1,
					pid: 0,
					children: [
						{
							name: "Node 1-2",
							id: 2,
							isLeaf: true,
							pid: 1
						}
					]
				},
				{
					name: "Node 2",
					id: 3,
					pid: 0,
				
				},
				{
					name: "Node 3",
					id: 4,
					pid: 0
				},
				{
					name: "Node 4",
					id: 5,
					pid: 0
				}
			])
		};
	},
	methods: {
		_addEventListener(container, box) {
			box.forEach(element => {
                console.log(element)
				element.addEventListener("touchstart", this.dragenter);
				element.addEventListener("touchcancel", this.dragleave);
				element.addEventListener("touchmove", this.dragover);
				element.addEventListener("drop", (e) => {
                  console.log(e)
                });
			});

			container.addEventListener("touchmove", this.dragstart);
			//container.addEventListener("touchend", this.dragend);
		},
		dragstart(e) {
             console.log("drag start!")

             this.$nextTick(function() {
                this.getParentID(e.target)
                console.log("dragged ", this.parentID);
             });
           
			setTimeout(() => {
				//this.classList.add("invisible");
			}, 0);
		},

		dragend(e) {
            console.log("dragend");
			//this.classList.remove("invisible");
			//this.classList.remove("drag_start");
		},

		dragenter(e) {
			e.preventDefault();

			console.log("dragenter");
			//this.classList.add("drag_enter");
		},

		dragleave(e) {
			console.log("dragleave");
			//this.classList.remove("drag_enter");
		},

		dragover(e) {
            console.log("dragover");
             this.$nextTick(function() {
                this.getParentID(e.target)
                console.log("dropped ", this.parentID);
             });
		},

		drop(e) {

             this.$nextTick(function() {
                this.getParentID(e.target)
                console.log("dropped ", this.parentID);
             });

			let container = document.querySelector(".box__dragabble");
			//this.classList.remove("drag_enter");
            //this.append(container);
            
            e.preventDefault();
		},

		onDrop() {
			console.log("dropped!");
		},
		onDel(node) {
			console.log(node);
			node.remove();
		},

		onChangeName(params) {
			console.log(params);
		},

		onAddNode(params) {
			console.log(params);
		},

		onClick(params) {
			console.log(params);
		},

		addNode() {
			var node = new TreeNode({ name: "new node", isLeaf: false });
			if (!this.data.children) this.data.children = [];
			this.data.addChildren(node);
		},

		getNewTree() {
			var vm = this;
			function _dfs(oldNode) {
				var newNode = {};

				for (var k in oldNode) {
					if (k !== "children" && k !== "parent") {
						newNode[k] = oldNode[k];
					}
				}

				if (oldNode.children && oldNode.children.length > 0) {
					newNode.children = [];
					for (
						var i = 0, len = oldNode.children.length;
						i < len;
						i++
					) {
						newNode.children.push(_dfs(oldNode.children[i]));
					}
				}
				return newNode;
			}

			vm.newTree = _dfs(vm.data);
		},

		getParentID(element) {
			if (
				typeof element.id == "undefined" ||
				typeof element.id == null ||
				element.id == "undefined" ||
				element.id == ""
			) {
				this.getParentID(element.parentElement);
			} else {
                this.parentID = element.id;
                
               // console.log(element.id)
			}
		}
	}
};
</script> 
 
<style lang="scss" scoped>
.vtl {
	.vtl-drag-disabled {
		background-color: #d0cfcf;
		&:hover {
			background-color: #d0cfcf;
		}
	}
	.vtl-disabled {
		background-color: #d0cfcf;
	}
}

.icon {
	&:hover {
		cursor: pointer;
	}
}
</style> 
 
