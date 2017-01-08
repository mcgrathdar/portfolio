
// var colors = new Array(
//   [62,35,255],
//   [60,255,60],
//   [255,35,98],
//   [45,175,230],
//   [255,0,255],
//   [255,128,0]);

// var step = 0;
// //color table indices for: 
// // current color left
// // next color left
// // current color right
// // next color right
// var colorIndices = [0,1,2,3];

// //transition speed
// var gradientSpeed = 0.002;

// function updateGradient()
// {
  
//   if ( $===undefined ) return;
  
// var c0_0 = colors[colorIndices[0]];
// var c0_1 = colors[colorIndices[1]];
// var c1_0 = colors[colorIndices[2]];
// var c1_1 = colors[colorIndices[3]];

// var istep = 1 - step;
// var r1 = Math.round(istep * c0_0[0] + step * c0_1[0]);
// var g1 = Math.round(istep * c0_0[1] + step * c0_1[1]);
// var b1 = Math.round(istep * c0_0[2] + step * c0_1[2]);
// var color1 = "rgb("+r1+","+g1+","+b1+")";

// var r2 = Math.round(istep * c1_0[0] + step * c1_1[0]);
// var g2 = Math.round(istep * c1_0[1] + step * c1_1[1]);
// var b2 = Math.round(istep * c1_0[2] + step * c1_1[2]);
// var color2 = "rgb("+r2+","+g2+","+b2+")";

//  $('#gradient').css({
//     background: "-webkit-gradient(linear, left top, right top, from("+color1+"), to("+color2+"))"}).css({
//     background: "-moz-linear-gradient(left, "+color1+" 0%, "+color2+" 100%)"});
  
//   step += gradientSpeed;
//   if ( step >= 1 )
//   {
//     step %= 1;
//     colorIndices[0] = colorIndices[1];
//     colorIndices[2] = colorIndices[3];
    
//     //pick two new target color indices
//     //do not pick the same as the current one
//     colorIndices[1] = ( colorIndices[1] + Math.floor( 1 + Math.random() * (colors.length - 1))) % colors.length;
//     colorIndices[3] = ( colorIndices[3] + Math.floor( 1 + Math.random() * (colors.length - 1))) % colors.length;
    
//   }
// }

// setInterval(updateGradient,10);



//custom.js






$(document).one('ready',function(){

var interrupteurBg2 = false;
var workOpen = false;
var aboutCVOpen = false;
var projectNumber = 1;


$('.identity').delay(300).animate({opacity : 1},1500);
$('.work-title').delay(1200).animate({height : "35px"},1000);
$('.work-title h5').delay(2000).fadeIn(1000);
$('.pointer').delay(1000).animate({height : "20%"},1000);




function next() {
    $('.about-landing').css({"width":"0%"});
    $('.about-details').css({"width":"100%"});
    $('.next-about').css({'display' : 'none'});
    $('.previous-about').css({'display' : 'block'});
    aboutCVOpen = true;
    switchBorderAbout(0);
    switchAboutButton(1);
  }
  
  function previous() {
    $('.about-landing').css({"width":"100%"});
    $('.about-details').css({"width":"0%"});
    $('.next-about').css({'display' : 'block'});
    $('.previous-about').css({'display' : 'none'});
    aboutCVOpen = false;
    switchAboutButton(1);
    switchBorderAbout(1); 
  }


function resetHome(){
    previous();
  }


function openWork(){ 
    $('.gradient').animate({height : "5%"},400);
    $('.work-section').animate({height : "95%"},400);
    $('.about-title').animate({bottom : "0"},50);
    $('.about-title').css({'display' : 'block'}, 1000);
    $('.work-title').css({'display' : 'none'}, 400);
    $('.identity').animate({top : "-200px"},400);
    $('.next-about img').css({'display' : 'none'});
   // $('h3.work-title').addClass('open', 5000);
    resetHome();
    supprBgWork();
   
  }
  

function openAbout(){
  about-title
      $('.gradient').animate({height : "95%"},400);
      $('.work-section').animate({height : "5%"},400);
      $('.about-title').animate({bottom : "0"},400);
      $('.identity').animate({top : "50%"},400);
      $('.work-title').css({'display': 'block'}, 1000);
      $('.about-title').css({'display': 'none'}, 400);
       $('.next-about img').css({'display' : 'block'});
       $('#work').fadeIn();
      $('#work-detail').slideUp();
     // $('h3.work-title').removeClass('open', 5000);
      aboutCVOpen=true;
      switchAboutButton(0); 
      return false;  
   
  }



function switchAboutButton(n){
    if(n === 0){
      $('.about-title').css({"cursor" : "default"});
    }
    
    if(n === 1){
      $('.about-title').css({"cursor" : "pointer"});
    }
  }

function switchPreviousnextAboutButton(n){
    if(n === 0){
      $('.next-about').css({'display':'none'});
      $('.previous-about').css({'display':'none'});
    }
    
    if(n === 1){
      $('.next-about').css({'display':'block'}); 
      $('.previous-about').css({'display':'none'});
    }
  }

  function supprBgWork(){
    $('.bg-workBloc').css({"background-image" : "none"});
    
    $('#bg-workBloc2').css({"background-image" : "none"});
  } 

  $(".overlay").css({'display':'none'});


   $("a .work-container").mouseenter(function(){
     $(this).find(".overlay").fadeIn(300);
    $(this).find(".overlay h2").fadeIn(1000);
  });
  
  $("a .work-container").mouseleave(function(){
    $(this).find(".overlay").fadeOut();
    $(this).find(".overlay h2").fadeOut(1000);
  });


  $('.about-title').click(openAbout);
  $('.work-title').click(openWork);
  $('.next-about').click(next);
  $('.previous-about').click(previous);
  

  $('.work-container').on('click', function(e) {
      $('#work').slideUp();
      $('#work-detail').slideDown();
      $('#contentarea').delay(480).fadeIn(400);
  });

  $('#close').on('click', function(e) {
      $('#work').slideDown();
      $('#work-detail').slideUp();
      $('#contentarea').fadeOut();
  });
});

