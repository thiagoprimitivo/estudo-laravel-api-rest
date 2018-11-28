var baseURL = "http://escola.test/api"

$(document).ready(function(){
    list();

    $('#list-body').on('click', '.delete-button', function(){
        var id = $(this).attr("data-id");

        excluir(id);
    });

});

function list() {
    $.ajax({
        type: "GET",
        url: `${baseURL}/students`,
        contentType: "application/json",
        success: function(students) {

            let content = '';

            for (const student of students.data) {
                content += `
                <tr>
                    <td>${student.id}</td>
                    <td>${student.name}</td>
                    <td>${student.birth}</td>
                    <td>${student.gender}</td>                  
                    <td>
                        <button class="delete-button" data-id="${student.id}">Excluir</button>
                    </td>                  
                </tr>
                `
            }

            $('#list-body').html(content);
        }
    });
}

function excluir(id){
    $.ajax({
        type: "DELETE",
        url: `${baseURL}/students/${id}`,
        contentType: "application/json",
        success: function(students) {
            alert('O aluno foi excluído com sucesso!');
            list();
        }
    });
}