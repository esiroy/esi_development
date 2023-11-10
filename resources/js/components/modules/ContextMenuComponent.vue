<template>
	<div class="container">
		<div class="row justify-content-center">
			<div class="contextMenu" v-on:click.right="openMenu">
				<h1 class="center">Right click anywhere to bring up a menu.</h1>
			</div>

			<div id="1" class="contextMenu">
				<h1 id="1" class="center menu">Right click anywhere to bring up a menu using event listener.</h1>
			
				<h1 id="2" class="center menu">Right click anywhere to bring up a menu using event listener.</h1>
		
                <h1 id="3" class="center menu">Right click anywhere to bring up a menu using event listener.</h1>
            </div>

		</div>

        <ul id="right-click-menu" tabindex="-1" v-if="viewMenu" v-bind:style="{ top: top, left: left }">
            <li>First list item</li>
            <li>Second list item</li>
        </ul>

	</div>
</template>

<script>
export default {
	mounted() {
        console.log("Component mounted.");

       document.addEventListener('click', () => this.closeMenu(event));

        let elements = document.getElementsByClassName("contextMenu");
        Array.from(elements).forEach((element) => {
            element.addEventListener('contextmenu', () => this.openMenu(event, element));
        });
        
        
	},
	data() {
        return {
            viewMenu: false,
            top: "0px",
            left: "0px"
        }
    },

	methods: {
		setMenu: function(event) {
			this.top = event.clientY + "px";
			this.left = event.clientX + "px";
		},
		closeMenu: function(e) {
           
            if(e.which == 3)
            {
                console.log(e)
            }

            console.log("closing menu...")
            this.viewMenu = false;
            
            e.preventDefault();
		},
		openMenu: function(e, element) {
            console.log(e.target.id)
            this.viewMenu = true;
            Vue.nextTick(function() {
                this.setMenu(e)
            }.bind(this))
			e.preventDefault();
		}
    },

};
</script>

<style scoped>
body {
	width: 100%;
	height: 100%;
}

h1 {
	font-size: 3em;
}

.center {
	text-align: center;
}

#demo {
	width: 100%;
	height: 100%;
}

#right-click-menu {
	background: #fafafa;
	border: 1px solid #bdbdbd;
	box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.14),
		0 3px 1px -2px rgba(0, 0, 0, 0.2), 0 1px 5px 0 rgba(0, 0, 0, 0.12);
	display: block;
	list-style: none;
	margin: 0;
	padding: 0;
	position: absolute;
	width: 250px;
	z-index: 999999;
}

#right-click-menu li {
	border-bottom: 1px solid #e0e0e0;
	margin: 0;
	padding: 5px 35px;
}

#right-click-menu li:last-child {
	border-bottom: none;
}

#right-click-menu li:hover {
	background: #1e88e5;
	color: #fafafa;
}

.menu {
    margin: 4px;
    border:1px solid #666;
    padding: 10px;
}
</style>