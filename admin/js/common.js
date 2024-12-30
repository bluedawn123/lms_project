
async function makeOption(e,step,target, ppcode){
  let cate = e.val();

  let data = new URLSearchParams({
    cate:cate,
    step:step,
    ppcode:ppcode
  });
 

  try{
    const response = await fetch('category_print.php',{
      method:'post',
      headers: { //전송되는 데이터의 타입
        'Content-Type': 'application/x-www-form-urlencoded' 
      },
      body:data
    });
    if(!response.ok){ //연결에러가 있다면
      throw new Error('연결에러');
    }
    const result = await response.text(); //응답의 결과를
    console.log(result);
    target.html(result);

  } catch(error){
    console.log(error);
  }
}