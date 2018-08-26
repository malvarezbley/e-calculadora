function selMoneda(tipoMoneda) {
    var f = document;
    f.getElementById("idMoneda").value = tipoMoneda;
    f.getElementById("idUF").className="boton-nosel";
    f.getElementById("idDolar").className="boton-nosel";
    f.getElementById("idEuro").className="boton-nosel";
    f.getElementById("idUTM").className="boton-nosel"; 
    var valorMoneda=0;
    switch (tipoMoneda) {
        case 'UF':
            f.getElementById("idUF").className="boton-sel";
            valorMoneda=f.getElementById("idValorUF").value;
            break;
        case 'Dolar':
            f.getElementById("idDolar").className="boton-sel";
            valorMoneda=f.getElementById("idValorDolar").value;
            break;
        case 'Euro':
            f.getElementById("idEuro").className="boton-sel";
            valorMoneda=f.getElementById("idValorEuro").value;
            break;
        case 'UTM':
            f.getElementById("idUTM").className="boton-sel";
            valorMoneda=f.getElementById("idValorUTM").value;
            break;
        default:
            f.getElementById("idUF").className="boton-sel";
    }


    if (f.getElementById("idFlagConversion").value=="1"){
        f.getElementById("idTituloForm").innerHTML=tipoMoneda + " a Pesos";
        f.getElementById("idLabelOrigen").innerHTML="Desde " + tipoMoneda;
        f.getElementById("idLabelDestino").innerHTML="a Pesos";
    }
    else{
        f.getElementById("idTituloForm").innerHTML=tipoMoneda + " a Pesos";
        f.getElementById("idLabelOrigen").innerHTML="Desde Pesos";
        f.getElementById("idLabelDestino").innerHTML=" a " + tipoMoneda;
    }
    f.getElementById("idLabelValorMoneda").innerHTML="Valor " + tipoMoneda + ": $ " + formatearNumero(valorMoneda); 
    f.getElementById("idValorMonedaSel").value=valorMoneda;

    calculaValor();

} 
            
function calculaValor(){
    var f=document;
    var tipoMoneda=f.getElementById("idMoneda").value;
    var valorMoneda=f.getElementById("idValorMonedaSel").value;

    if (f.getElementById("idFlagConversion").value=="1"){
        if(f.getElementById("idValorInput").value=="")
            f.getElementById("idValorOutput").value="";
        else
            f.getElementById("idValorOutput").value=formatearNumero(Math.round(parseFloat(valorMoneda) * parseFloat(f.getElementById("idValorInput").value)));
    }
    else
        {
        if(f.getElementById("idValorInput").value=="")
            f.getElementById("idValorOutput").value="";
        else{
            var valorM=parseFloat(f.getElementById("idValorInput").value) / parseFloat(valorMoneda);
            valorM=formatearNumero(Math.round(valorM * 100)/100);
            f.getElementById("idValorOutput").value=valorM;
            }
        }
}
            
function formatearNumero(nStr) {
    nStr += '';
    var x = nStr.split('.');
    var x1 = x[0];
    var x2 = x.length > 1 ? ',' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + '.' + '$2');
    }
    return x1 + x2;
 }            
            
function invertirValores(){
    var f=document;
    if(f.getElementById("idFlagConversion").value=="1"){
        f.getElementById("idTituloForm").innerHTML="Pesos a " + f.getElementById("idMoneda").value;
        f.getElementById("idLabelOrigen").innerHTML="Desde Pesos ";
        f.getElementById("idLabelDestino").innerHTML="a " + f.getElementById("idMoneda").value;
        f.getElementById("idFlagConversion").value="2";
        }
    else{
        f.getElementById("idTituloForm").innerHTML=f.getElementById("idMoneda").value + " a Pesos";
        f.getElementById("idLabelOrigen").innerHTML="Desde " + f.getElementById("idMoneda").value;
        f.getElementById("idLabelDestino").innerHTML="a Pesos";
        f.getElementById("idFlagConversion").value="1";
    }

    var str=f.getElementById("idValorOutput").value;
    var Re = RegExp("\\.", "g");
    str=str.replace(Re,"");
    str=str.replace(",","");
    f.getElementById("idValorInput").value=str.replace(".","");
    calculaValor();
}    
