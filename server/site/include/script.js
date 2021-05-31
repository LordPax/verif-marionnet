let idReq = 1
$(() => {
    $('.event').on('mousedown', '.more-btn', function(e){
        this.addEventListener('click', event => event.preventDefault())
        $(this).parent().parent().children('.section-content').toggle()
    })

    $('.add-request').click(function(e) {
        e.preventDefault()
        // this.addEventListener('click', event => event.preventDefault())
        idReq++
        $.post('http://localhost/server/site/include/render_form.php', {
            type : 'req',
            idReq
        }, data => {
            $(this).parent().parent().children('.section-content').append(data)
            // console.log(data)
        })
    })
})