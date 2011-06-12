

function meses(m){
	m = parseFloat(m);
	switch(m){
		case 1: return "enero";
		case 2: return "febrero";
		case 3: return "marzo";
		case 4: return "abril";
		case 5: return "mayo";
		case 6: return "junio";
		case 7: return "julio";
		case 8: return "agosto";
		case 9: return "septiembre";
		case 10: return "octubre";
		case 11: return "noviembre";
		case 12: return "diciembre";				
	}
}








function tr(s, o){
    if(o){
        return "<tr "+o+">"+s+"</tr>";  
    }else{
        return "<tr >"+s+"</tr>";   
    }
}

function td(s, o){
    if(o){
        return "<td "+o+">"+s+"</td>";
    }else{
        return "<td >"+s+"</td>";
    }
}

function div(s, o){
    if(o){
        return "<div "+o+">"+s+"</div>";
    }else{
        return "<div >"+s+"</div>";
    }


}

