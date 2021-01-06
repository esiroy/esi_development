


<div class="member mt-3">
    <div class="container">
        <div class="row">
            <div class="col-md-2">Name</div>
            <div class="col-md-9">
                {{ $member->lastname  ?? ' - ' }},
                {{ $member->firstname  ?? ' - ' }}
            </div>
        </div>
        <div class="row">
            <div class="col-md-2">Agent</div>
            <div class="col-md-9">
                {{ $agentInfo->user->firstname  ?? ' - ' }}
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-2">Tutor</div>
            <div class="col-md-9">

                {{ $tutorInfo->user->japanese_firstname  ?? ' - ' }}
            </div>
        </div>

        <!--
        <div class="row">
            <div class="col-md-2">Lesson Class</div>
            <div class="col-md-9">
                毎月 {{$memberAttribute->lesson_limit ?? '0'}} 回クラス (あと　残り {{$memberAttribute->lesson_limit ?? '0'}}回)
            </div>
        </div>
        -->
    </div>
</div>