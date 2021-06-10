$(document).ready(function() {
    $('.your-bet-section').on('click', '.bet-btn', function (e) {
        var $this = $(this);
        var parent = $this.parent();
        var matchId = parent.data('match_id');
        var formData = new FormData();
        formData.append('match_id', matchId);
        var _token = $('meta[name="csrf-token"]').attr('content');
        formData.append('_token', _token);
        var betId = $this.data('bet_id');
        var userId = $('meta[name="user-id"]').attr('content');
        formData.append('user_id', userId);
        formData.append('win_team_id', betId);
        $.ajax({
            url: "/bettings/ajaxSubmit",
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
        }).done(function( data ) {
            console.log(data.success);
            if (data.success == 1) {
                parent.find('.bet-btn').removeClass('active');
                $this.addClass('active');
            }
        });

    })
} );
