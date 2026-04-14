document.querySelector("body").setAttribute("oncontextmenu", "return false")

window.addEventListener("scroll", ()=>{
    let offset = window.scrollY
    if(offset > 10){
        document.querySelector("nav").style.transform = "translateY(-60px)"
    }else{
        document.querySelector("nav").style.transform = "translateY(0px)"
    }
})

//theme logic
let weather = document.querySelector(".weather")
let w2 = document.querySelector(".w2")
let themeToggle = document.querySelector('.mode_set')
let setTheme = localStorage.getItem("mode")

if(setTheme == "dark"){
  document.querySelector("body").setAttribute('class', setTheme)
  weather.setAttribute("src", "./assets/icons/moon.png")
  themeToggle.parentElement.classList.toggle("clicked")
}else if(setTheme == "light"){
  document.querySelector("body").setAttribute("class", "")
  weather.setAttribute("src", "./assets/icons/sun.png")
  // themeToggle.parentElement.classList.toggle("")
}

themeToggle.addEventListener("click", ()=>{
  document.querySelector("body").classList.toggle("dark")
  weather.setAttribute("src", "./assets/icons/moon.png")
  themeToggle.parentElement.classList.toggle("clicked")
  let theme = document.querySelector('body').getAttribute('class')
  if(theme == ""){
    theme = "light"
    weather.setAttribute("src", "./assets/icons/sun.png")
  }
  localStorage.setItem("mode", theme)
})

//alert function
const alert_message = document.querySelector(".alert")
document.querySelector(".close").addEventListener("click", ()=>{
  alert_message.setAttribute("style", "display: none;")

})