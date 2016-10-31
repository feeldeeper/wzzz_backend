function ChangeToBig(value){
	   if (value == null || value=="") {
	   		//return "错误金额";
	   		return "";
	   }
	   
	   value = "" + value;    
       
       var intFen,i;
       var strArr,strCheck,nstrCheck,strFen,strDW,strNum,strBig,strNow;
               
        var v = -1;
        try {
        	v = parseFloat(value);
        }
        catch (ex) {
        }
        
        if (isNaN(v) || v <= 0 || v > 999999999999.99) {
        	return "零元整";
        }
        else if (v >= 1 && value.charAt(0) == '0') {
        	return "错误金额";
        }        
        else if (value.charAt(0) == '+') {
        	return "错误金额";
        }
        
        if (value.indexOf("e") >=0 || value.indexOf("E") >=0) {
        	return "错误金额";
        }
        
        var strArray = value.split(".");
        if ((strArray.length == 2 && strArray[0]=="") || (strArray[1] && strArray[1].length > 2)) {     //小数点后面超过两位提示
        	return "错误金额";
        }

        try
        {
                i = 0;
                strBig = "";
                intFen = parseInt(value*100+0.00099999999);          //转换为以分为单位的数值
                strFen = intFen.toString();
                strArr = strFen.split(".");
                strFen = strArr[0];
                intFen = strFen.length;      //获取长度
                strArr = strFen.split("");   //将各个数值分解到数组内                
                while(intFen!=0)             //分解并转换
                {
                        i = i+1;
                        switch(i)           //选择单位
                        {
                                case 1:strDW = "分";break;
                                case 2:strDW = "角";break;
                                case 3:strDW = "元";break;
                                case 4:strDW = "拾";break;
                                case 5:strDW = "佰";break;
                                case 6:strDW = "仟";break;
                                case 7:strDW = "万";break;
                                case 8:strDW = "拾";break;
                                case 9:strDW = "佰";break;
                                case 10:strDW = "仟";break;
                                case 11:strDW = "亿";break;
                                case 12:strDW = "拾";break;
                                case 13:strDW = "佰";break;
                                case 14:strDW = "仟";break;
                        }
                                
                        switch (strArr[intFen-1])  //选择数字
                        {
                                case "1":strNum = "壹";break;
                                case "2":strNum = "贰";break;
                                case "3":strNum = "叁";break;
                                case "4":strNum = "肆";break;
                                case "5":strNum = "伍";break;
                                case "6":strNum = "陆";break;
                                case "7":strNum = "柒";break;
                                case "8":strNum = "捌";break;
                                case "9":strNum = "玖";break;
                                case "0":strNum = "零";break;
                        }

                        //处理特殊情况
                        strNow = strBig.split("");
                        //分为零时的情况
                        if((i==1)&&(strArr[intFen-1]=="0"))
                        {
                                strBig = "整";
                        }
                        //角为零时的情况
                        else if((i==2)&&(strArr[intFen-1]=="0"))
                        {    //角分同时为零时的情况
                                if(strBig!="整")
                                strBig = "零"+strBig;
                        }
                        //元为零的情况
                        else if((i==3)&&(strArr[intFen-1]=="0"))
                        {
                                strBig = "元"+strBig;
                        }
                        //拾－仟中一位为零且其前一位（元以上）不为零的情况时补零
                        else if((i<7)&&(i>3)&&(strArr[intFen-1]=="0")&&(strNow[0]!="零")&&(strNow[0]!="元"))
                        {
                                strBig = "零"+strBig;
                        }
                        //拾－仟中一位为零且其前一位（元以上）也为零的情况时跨过
                        else if((i<7)&&(i>3)&&(strArr[intFen-1]=="0")&&(strNow[0]=="零")){}                                 
                        //拾－仟中一位为零且其前一位是元且为零的情况时跨过
                        else if((i<7)&&(i>3)&&(strArr[intFen-1]=="0")&&(strNow[0]=="元")){}                                
                        //当万为零时必须补上万字
                        else if((i==7)&&(strArr[intFen-1]=="0"))
                        {
                                strBig ="万"+strBig;
                        }     
                        //拾万－仟万中一位为零且其前一位（万以上）不为零的情况时补零
                        else if((i<11)&&(i>7)&&(strArr[intFen-1]=="0")&&(strNow[0]!="零")&&(strNow[0]!="万"))
                        {
                                strBig = "零"+strBig;
                        }
                        //拾万－仟万中一位为零且其前一位（万以上）也为零的情况时跨过
                        else if((i<11)&&(i>7)&&(strArr[intFen-1]=="0")&&(strNow[0]=="万")){}
                        //拾万－仟万中一位为零且其前一位为万位且为零的情况时跨过
                        else if((i<11)&&(i>7)&&(strArr[intFen-1]=="0")&&(strNow[0]=="零")){}
                        //万位为零且存在仟位和十万以上时，在万仟间补零
                        else if((i<11)&&(i>8)&&(strArr[intFen-1]!="0")&&(strNow[0]=="万")&&(strNow[2]=="仟"))
                        {
                                strBig = strNum+strDW+"万零"+strBig.substring(1,strBig.length);
                        }
                        //单独处理亿位
                        else if(i==11)
                        {
                                //亿位为零且万全为零存在仟位时，去掉万补为零
                                if((strArr[intFen-1]=="0")&&(strNow[0]=="万")&&(strNow[2]=="仟"))
                                {
                                        strBig ="亿"+"零"+strBig.substring(1,strBig.length);
                                }
                                //亿位为零且万全为零不存在仟位时，去掉万
                                else if((strArr[intFen-1]=="0")&&(strNow[0]=="万")&&(strNow[2]!="仟"))
                                {
                                        strBig ="亿"+strBig.substring(1,strBig.length);
                                }
                                //亿位不为零且万全为零存在仟位时，去掉万补为零
                                else if((strNow[0]=="万")&&(strNow[2]=="仟"))
                                {
                                        strBig = strNum+strDW+"零"+strBig.substring(1,strBig.length);
                                }
                                //亿位不为零且万全为零不存在仟位时，去掉万        
                                else if((strNow[0]=="万")&&(strNow[2]!="仟"))
                                {
                                        strBig = strNum+strDW+strBig.substring(1,strBig.length);
                                }
                                //其他正常情况
                                else
                                {
                                        strBig = strNum+strDW+strBig;
                                }
                        }
                        //拾亿－仟亿中一位为零且其前一位（亿以上）不为零的情况时补零
                        else if((i<15)&&(i>11)&&(strArr[intFen-1]=="0")&&(strNow[0]!="零")&&(strNow[0]!="亿"))
                        {
                                strBig = "零"+strBig;
                        }
                        //拾亿－仟亿中一位为零且其前一位（亿以上）也为零的情况时跨过
                        else if((i<15)&&(i>11)&&(strArr[intFen-1]=="0")&&(strNow[0]=="亿")){}
                        //拾亿－仟亿中一位为零且其前一位为亿位且为零的情况时跨过
                        else if((i<15)&&(i>11)&&(strArr[intFen-1]=="0")&&(strNow[0]=="零")){}
                        //亿位为零且不存在仟万位和十亿以上时去掉上次写入的零
                        else if((i<15)&&(i>11)&&(strArr[intFen-1]!="0")&&(strNow[0]=="零")&&(strNow[1]=="亿")&&(strNow[3]!="仟"))
                        {
                                strBig = strNum+strDW+strBig.substring(1,strBig.length);
                        }
                        //亿位为零且存在仟万位和十亿以上时，在亿仟万间补零
                        else if((i<15)&&(i>11)&&(strArr[intFen-1]!="0")&&(strNow[0]=="零")&&(strNow[1]=="亿")&&(strNow[3]=="仟"))
                        {
                                strBig = strNum+strDW+"亿零"+strBig.substring(2,strBig.length);
                        }
                        else
                        {
                                strBig = strNum+strDW+strBig;
                        }
                                
                        strFen = strFen.substring(0,intFen-1);
                        intFen = strFen.length;
                        strArr = strFen.split("");
                }
                if(strBig.indexOf("undefined") != -1)
                {
        	        return "错误金额";
                }
                return strBig;
        }
        catch(err)
        {
                return "";      //若失败则返回原值
        }        
}

function outputMoney(number) { 
number=number.replace(/\,/g,"");
if (isNaN(number)||number=="") return ""; 
number = Math.round( number*100) /100; 
if(number<0) 
return '-'+outputDollars(Math.floor(Math.abs(number)-0) + '') + outputCents(Math.abs(number) - 0); 
else 
return outputDollars(Math.floor(number-0) + '') + outputCents(number - 0); 
} 
function outputDollars(number) 
{ 
if (number.length<= 3) 
return (number == '' ? '0' : number); 
else 
{ 
     var mod = number.length%3; 
     var output = (mod == 0 ? '' : (number.substring(0,mod))); 
     for (i=0 ; i< Math.floor(number.length/3) ; i++) 
     { 
       if ((mod ==0) && (i ==0)) 
       output+= number.substring(mod+3*i,mod+3*i+3); 
       else 
       output+= ',' + number.substring(mod+3*i,mod+3*i+3); 
     } 
     return (output); 
} 
} 
function outputCents(amount) 
{ 
amount = Math.round( ( (amount) - Math.floor(amount) ) *100); 
return (amount<10 ? '.0' + amount : '.' + amount); 
}