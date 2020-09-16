<template>
    <div class="table-responsive">
        <table class="table table-bordered">
        <thead>
            <tr>

                <th class="small text-center">Agent Name</th>
                <th class="small text-center">ID</th>
                <th class="small text-center">Area</th>
                <th class="small text-center">Member<br/>List</th>
                <th class="small text-center">First Date of<br/>Purchase</th>
                <th class="small text-center">Point Purchase<br/>History</th>
                
                <th class="small text-center">Point<br/>Balance</th>
                <th class="small text-center">Expire<br/>Data</th>
                <th class="small text-center">Purchase<br/>Amount</th>
                <th class="small text-center">Action</th>                                    
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="small">William Willamson</td>
                <td class="small">25</td>
                <td class="small text-center">~</td>
                <td class="small text-center"><img src="/images/iMemberList.jpg"></td>
                <td class="small text-center">April 30, 2018</td>
                <td class="small text-center"><img src="/images/iHistory.jpg"></td>
                <td class="small text-center">199.0</td>
                <td></td>
                <td class="small text-center">215.99</td>
                <td class="small text-center">Account | Edit  | Delete </td>
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
