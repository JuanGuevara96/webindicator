function FormatMoney(value, n=2){ //formato de dinero js
    value = npoint(cleanstr(value), n);
    var parts = value.toString().split(".");
      parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
      return parts.join(".");
  }

  function cleanstr(value){ //limpia formato de cadena
    value = value.toString().replace(/(?!-)[^0-9.]/g, "");
    return  (value > 0) ? parseFloat(value): 0;
  }

  function npoint(value, n=2){ //redondeo decimal
    value = parseFloat(value);
    var pow = Math.pow(10, n);
    value = Math.round((value + Number.EPSILON) * pow) / pow;
    return parseFloat(value.toFixed(n));
  }