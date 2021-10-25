<div class="modal scale fade" id="createGameFlavorReportModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{!! __('messages.report_game_title') !!}</h4>
            </div>
            <div class="modal-body" style="max-height: none">
                @include('game_flavor_report.forms.create_edit')
            </div>
        </div><!--.modal-content-->
    </div><!--.modal-dialog-->
</div><!--.modal-->
