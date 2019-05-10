<link rel="stylesheet" type="text/css"
      href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css"/>
<link rel="stylesheet" type="text/css"
      href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css"/>

<script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

<script>
    $('.gallery').owlCarousel({
        loop: true,
        dots: true,
        margin: 30,
        nav: true,
        autoHeight:true,

        responsive: {
            0: {
                items: 1,
                center: true
            },
            600: {
                items: 2
            },
            1000: {
                items: 3
            }
        }
    })
</script>