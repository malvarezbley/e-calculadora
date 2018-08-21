function selMoneda(tipoMoneda){
    var f=document;
    f.getElementById("idMoneda").value=tipoMoneda;
    f.getElementById("idUF").className="boton-nosel";
    f.getElementById("idDolar").className="boton-nosel";
    f.getElementById("idEuro").className="boton-nosel";
    f.getElementById("idUTM").className="boton-nosel"; 
    switch (tipoMoneda) {
        case 'UF':
            f.getElementById("idUF").className="boton-sel";
            var valorMoneda=f.getElementById("idValorUF").value;
            break;
        case 'Dolar':
            f.getElementById("idDolar").className="boton-sel";
            var valorMoneda=f.getElementById("idValorDolar").value;
            break;
        case 'Euro':
            f.getElementById("idEuro").className="boton-sel";
            var valorMoneda=f.getElementById("idValorEuro").value;
            break;
        case 'UTM':
            f.getElementById("idUTM").className="boton-sel";
            var valorMoneda=f.getElementById("idValorUTM").value;
            break;
        default:
            f.getElementById("idUF").className="boton-sel";
    }

    if (f.getElementById("idFlagConversion").value=="1"){
        f.getElementById("idTituloForm").innerHTML="Calculadora: " + tipoMoneda + " a Pesos";
        f.getElementById("idLabelOrigen").innerHTML="Convertir desde " + tipoMoneda;
        f.getElementById("idLabelDestino").innerHTML="Pesos";
    }
    else{
        f.getElementById("idTituloForm").innerHTML="Calculadora: " + tipoMoneda + " a Pesos";
        f.getElementById("idLabelOrigen").innerHTML="Convertir desde Pesos";
        f.getElementById("idLabelDestino").innerHTML=tipoMoneda;
    }
    f.getElementById("idValorMoneda").innerHTML="Valor " + tipoMoneda + ": " + formatearNumero(valorMoneda); 
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
    x = nStr.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? ',' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + '.' + '$2');
    }
    return x1 + x2;
 }            
            
function invertirValores(){
    var f=document;
    if(f.getElementById("idFlagConversion").value=="1"){
        f.getElementById("idTituloForm").innerHTML="Calculadora: Pesos a " + f.getElementById("idMoneda").value;
        f.getElementById("idLabelOrigen").innerHTML="Convertir desde Pesos ";
        f.getElementById("idLabelDestino").innerHTML=f.getElementById("idMoneda").value;
        f.getElementById("idFlagConversion").value="2";
        }
    else{
        f.getElementById("idTituloForm").innerHTML="Calculadora: " + f.getElementById("idMoneda").value + " a Pesos";
        f.getElementById("idLabelOrigen").innerHTML="Convertir desde " + f.getElementById("idMoneda").value;
        f.getElementById("idLabelDestino").innerHTML="Pesos";
        f.getElementById("idFlagConversion").value="1";
    }

    str=f.getElementById("idValorOutput").value;
    var Re = RegExp("\\.", "g");
    str=str.replace(Re,"");
    str=str.replace(",","");
    f.getElementById("idValorInput").value=str.replace(".","");
    calculaValor();
}    
