<template>
    <div class="table-responsive">
        <table class="table table-bordered">
        <thead>
            <tr>
                 <th class="small text-center">Sort</th>
                <th class="small text-center">ID</th>
                <th class="small text-center">Name</th>
                <th class="small text-center">Member (Main)</th>
                <th class="small text-center">Member (Support)</th> 
                <th class="small text-center">Action</th>                                    
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="small">100</td>
                <td class="small">1</td>
                <td class="small">Roy Abellana</td>
                <td class="small text-center"><img src="/images/iMemberMain.gif"></td>
                <td class="small text-center"><img src="/images/iMemberSupport.gif"></td>                
                <td class="small text-center">Edit  | Delete</td>
            </tr>
        </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
