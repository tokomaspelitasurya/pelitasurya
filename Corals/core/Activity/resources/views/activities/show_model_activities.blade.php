<div class="row model-activity-modal">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th><i class="fa fa-user-o"></i> @lang('Activity::attributes.activity.causer_id')</th>
                    <th><i class="fa fa-clock-o m-l-5"></i> @lang('Corals::attributes.created_at')</th>
                    <th>@lang('Activity::attributes.activity.properties')</th>
                </tr>
                </thead>
                <tbody>
                @forelse($activities as $activity)
                    <tr>
                        <td>{!!  $activity->present('causer_id') !!}</td>
                        <td>{!!  $activity->present('created_at') !!}</td>
                        <td>{!! formatProperties($activity->getProperties())  !!}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">No Records Found!!</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
