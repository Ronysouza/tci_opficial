$(document).ready(function(){
    $("#campoPesquisa").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#tablePrint tbody tr").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
});

async function imprimir(){
    const divPrint = document.getElementById('tablePrint');
    newWin = window.open('');
    newWin.document.write('<link href="./main.css" rel="stylesheet">');
    newWin.document.write('<link href="./assets/css/print.css" rel="stylesheet">');
    newWin.document.write('<button class="btn m-2 bg-primary noPrint" onclick="window.print();window.close()"><i class="fa fa-print text-white"></i></button><br><br><h5 class="mb-3">Clientes Cadastrados</h5>');
    newWin.document.write(divPrint.outerHTML);
    //await new Promise(r => setTimeout(r, 150));
    //newWin.print();
    //newWin.close();
}

function carregaServicos(self){
    $(".c-"+$(self).val()).removeClass('d-none');
}

function calcDuracao(){
    inicio = new Date($('#dataInicial').val());
    fim = new Date($('#dataFinal').val());
    diferenca = fim.getTime() - inicio.getTime();
    $('#duracao').val(parseInt(diferenca / 2592000000));
}

function inserirServico(){
    $('#tblServicos').append(`
        <tr servico="`+$('#servico').val()+`" valor="`+$('#valor').val()+`" codigo="" class="border-bottom">
            <td>`+$('#servico option:selected').text()+`</td>
            <td style="width:14%" class="btn-danger text-center" onclick="removerServico(this)"><i class="fas fa-trash-alt"></i></td>
        </tr>
    `);
    $('#servico').val(0);
    $('#valor').val(0);
}

function removerServico(self){
    $($(self).parent()).remove();
}

function primeiroPagamento(self){
    $('#diaVencimento').val($(self).val().split('-')[2]);
}

function enviar(){
    let servicos = $('#tblServicos tr');
    let lstServer = [];

    for(let i = 0; i < servicos.length; i++){
        temp = servicos[i];
        lstServer.push({'servico':$(temp).attr('servico'),'valor':$(temp).attr('valor'),'codigo':$(temp).attr('codigo')});
    }

    $('#servicos').val(JSON.stringify(lstServer));

    $('#frmEnviar').submit();

    //$('#needs-validation').click();
}

function valorServico(){
    $('#valor').val(parseFloat($('#servico option:selected').attr('valor')).toFixed(2));
}