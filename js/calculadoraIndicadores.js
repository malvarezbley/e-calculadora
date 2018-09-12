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




// formatea un numero según una mascara dada ej: "-$###,###,##0.00"
//
// elm   = elemento html <input> donde colocar el resultado
// n     = numero a formatear
// mask  = mascara ej: "-$###,###,##0.00"
// force = formatea el numero aun si n es igual a 0
//
// La función devuelve el numero formateado

function MASK(form, n, mask, format) {
  if (format == "undefined") format = false;
  if (format || NUM(n)) {
    dec = 0, point = 0;
    x = mask.indexOf(".")+1;
    if (x) { dec = mask.length - x; }

    if (dec) {
      n = NUM(n, dec)+"";
      x = n.indexOf(".")+1;
      if (x) { point = n.length - x; } else { n += "."; }
    } else {
      n = NUM(n, 0)+"";
    } 
    for (var x = point; x < dec ; x++) {
      n += "0";
    }
    x = n.length, y = mask.length, XMASK = "";
    while ( x || y ) {
      if ( x ) {
        while ( y && "#0.".indexOf(mask.charAt(y-1)) == -1 ) {
          if ( n.charAt(x-1) != "-")
            XMASK = mask.charAt(y-1) + XMASK;
          y--;
        }
        XMASK = n.charAt(x-1) + XMASK, x--;
      } else if ( y && "$0".indexOf(mask.charAt(y-1))+1 ) {
        XMASK = mask.charAt(y-1) + XMASK;
      }
      if ( y ) { y-- }
    }
  } else {
     XMASK="";
  }
  if (form) { 
    form.value = XMASK;
    if (NUM(n)<0) {
      form.style.color="#FF0000";
    } else {
      form.style.color="#000000";
    }
  }
  return XMASK;
}

// Convierte una cadena alfanumérica a numérica (incluyendo formulas aritméticas)
//
// s   = cadena a ser convertida a numérica
// dec = numero de decimales a redondear
//
// La función devuelve el numero redondeado

function NUM(s, dec) {
  for (var s = s+"", num = "", x = 0 ; x < s.length ; x++) {
    c = s.charAt(x);
    if (".-+/*".indexOf(c)+1 || c != " " && !isNaN(c)) { num+=c; }
  }
  if (isNaN(num)) { num = eval(num); }
  if (num == "")  { num=0; } else { num = parseFloat(num); }
  if (dec != undefined) {
    r=.5; if (num<0) r=-r;
    e=Math.pow(10, (dec>0) ? dec : 0 );
    return parseInt(num*e+r) / e;
  } else {
    return num;
  }
}