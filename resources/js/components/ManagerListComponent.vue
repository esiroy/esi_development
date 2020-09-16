<template>
    <div class="table-responsive">
        <table class="table table-bordered">
        <thead>
            <tr>
                <th class="small text-center">Name</th>
                <th class="small text-center">Username</th>                
                <th class="small text-center">Action</th>                                    
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="small">Roy Abellana</td>
                <td class="small text-center">esi.roy.dev</td>                
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
