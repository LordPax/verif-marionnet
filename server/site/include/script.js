let idReq = 1
let idTabRes = [1]
// const domain = 'http://www-info.iutv.univ-paris13.fr/~gauthier/site' 
const domain = 'http://localhost/server/site' 

$(() => {
    $('.event').on('mousedown', '.more-btn', function(e) {
        this.addEventListener('click', event => event.preventDefault())
        $(this).parent().parent().children('.section-content').toggle()
    })

    $('.add-request').click(function(e) {
        e.preventDefault()
        idReq++
        idTabRes = [...idTabRes, 1]

        $.post(domain + '/include/render_form.php', {
            type : 'req',
            idReq
        }, data => {
            $(this).parent().parent()
            .children('.section-content')
            .append(data)
        })
    })

    $('.event').on('mousedown', '.add-response', function(e) {
        this.addEventListener('click', event => event.preventDefault())
        const id = $(this).parent().parent().parent().parent().data('idreq')
        console.log(id)
        idTabRes[id - 1]++

        $.post(domain + '/include/render_form.php', {
            type : 'res',
            idReq : id,
            idRes : idTabRes[id - 1]
        }, data => {
            $(this).parent().parent()
            .children('.section-content')
            .append(data)
        })
    })

    $('.msg-close').click(function(e) {
        $(this).parent().hide()
    })
})