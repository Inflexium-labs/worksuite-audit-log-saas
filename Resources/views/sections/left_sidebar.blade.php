<li>
    <a href="javascript:;" class="waves-effect"><i class="fa fa-history"></i>
        <span class="hide-menu">
            Audit Log
            <span class="fa arrow"></span>
        </span>
    </a>
    <ul class="nav nav-second-level">
        <li>
            <a href="{{route('admin.audit-log.log-activities')}}" class="waves-effect">
                <span class="hide-menu">Log Activities<span>
            </a>
        </li>
        <li>
            <a href="{{route('admin.audit-log.log-activities.attendance')}}" class="waves-effect">
                <span class="hide-menu">Attendance<span>
            </a>
        </li>
        <li>
            <a href="{{route('admin.audit-log.user')}}" class="waves-effect">
                <span class="hide-menu">User Logs<span>
            </a>
        </li>
        <li>
            <a href="{{route('admin.audit-log.task')}}" class="waves-effect">
                <span class="hide-menu">Task Logs<span>
            </a>
        </li>
        <li>
            <a href="{{route('admin.audit-log.project')}}" class="waves-effect">
                <span class="hide-menu">Project Logs<span>
            </a>
        </li>
    </ul>
</li>
