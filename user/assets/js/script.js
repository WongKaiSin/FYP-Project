/*----- Image Slider -----*/
let slideIndex = 1;
showSlides(slideIndex);

// Next/previous controls
function plusSlides(n) {
  showSlides(slideIndex += n);
}

// Thumbnail image controls
function currentSlide(n) {
  showSlides(slideIndex = n);
}

function showSlides(n) {
  let i;
  let slides = document.getElementsByClassName("mySlides");
  let dots = document.getElementsByClassName("demo");
//   let captionText = document.getElementById("caption");
  if (n > slides.length) {slideIndex = 1}
  if (n < 1) {slideIndex = slides.length}
  for (i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";
  }
  for (i = 0; i < dots.length; i++) {
    dots[i].className = dots[i].className.replace(" active", "");
  }

  if (slides[slideIndex - 1]) {
    slides[slideIndex - 1].style.display = "block";
    dots[slideIndex - 1].className += " active";
    // captionText.innerHTML = dots[slideIndex - 1].alt;
  }
}
/*----- END Image Slider -----*/

function addCommas(number) {
  number += '';
  var x = number.split('.');
  var x1 = x[0];
  var x2 = x.length > 1 ? '.' + x[1] : '';

  var rgx = /(\d+)(\d{3})/;
  while (rgx.test(x1)) {
      x1 = x1.replace(rgx, '$1' + ',' + '$2');
  }

  return x1 + x2;
}

var domain = "https://localhost:80/FYP-Project";
// plus and minus button
$(document).ready(function() {
  $('.minus').click(function () {
      var $input = $(this).siblings('input');
      var count = parseInt($input.val()) - 1;
      count = count < 1 ? 1 : count;
      $input.val(count);
      $input.change();
      return false;
  });

  $('.plus').click(function () {
      var $input = $(this).siblings('input');
      var value = (parseInt($input.val()) + 1);
      var max = parseInt($input.attr('data-max'));

      if (value > max && max != "limit") {
          alert('Only have ' + addCommas(max) + ' units available.');
          value = max;
      }
  });

  $(document).on("change", ".attribute", function() {
      var variations = "";
      var ProID = $("#ProID").val();
      var count = 0,
          selected = 0;

      $("#bg-overlay").addClass("loading").fadeIn();
      $(".attribute").each(function() {
          if ($(this).val() != "") {
              selected++;
          }

          count++;
      });

      $(".attribute option:selected").each(function() {
          variations += $(this).val().replace("#", "-");
      });

      $("#BtnSelect").hide();
      $("#BtnOutStock").hide();

      var result = $.ajax({
          type: "GET",
          url: domain + "/user/lib/ajax.php?action=stock&ProID=" + ProID,
          async: false,
      }).responseText; {
          var data = $.parseJSON(result);

          if (result != "") {
              var ProStock = data["ProStock"];

              if (ProStock > 0) // if have stock
              {
                  if ($("#qty-box" + ProID).val() > ProStock) {
                      $("#qty-box" + ProID).val(ProStock);
                  }
                  $(".product-info-stock").show();
                  $("#qty-box" + ProID).attr("data-max", ProStock);
                  $("#BtnCart").show();
                  $("#BtnOutStock").hide();
              } else {
                  $(".product-info-stock").hide();
                  $("#BtnCart").hide();

                  if (count == selected) {
                      $("#BtnOutStock").show();
                  }
              }
          } else // show out of stock if empty result
          {
              $(".product-info-stock").hide();
              $("#BtnCart").hide();
              $("#BtnOutStock").show();
          }
      }

      setTimeout(function() {
          $("#bg-overlay").removeClass("loading").fadeOut();
      }, 200)
  });


  /*** product quantity control ***/
  $(document).on("click", ".quantity-box > button", function() {
      var id = $(this).attr("data-rel");
      var qty = parseInt($("#qty-box" + id).val());
      var QtyMax = parseInt($("#qty-box" + id).attr("data-max"));
      var action = $(this).val();
      var NewQty = 0;

      if (action == "-") {
          NewQty = qty - 1;
      } else if (action == "+") {
          NewQty = qty + 1;
      }

      if (NewQty <= 0) {
          NewQty = 1;
      }

      if (NewQty > QtyMax && QtyMax != "limit") {
          $("#bg-overlay").fadeIn();
          $("#popup-alert-text").html('Only have ' + addCommas(QtyMax) + ' units available.');
          $("#popup-alert-box").fadeIn();
          NewQty = QtyMax;
      }

      $("#qty-box" + id).val(NewQty);
  });

  $(document).on('keyup', 'input[name^=ProQty]', function() {
      var qty = $(this).val();
      var QtyMax = parseInt($(this).attr("data-max"));

      if (isNaN(qty) || qty <= 0) {
          $(this).val(1);
      }

      if (qty > QtyMax && QtyMax != "limit") {
          $("#bg-overlay").fadeIn();
          $("#popup-alert-text").html('Only have ' + addCommas(QtyMax) + ' units available.');
          $("#popup-alert-box").fadeIn();
          $(this).val(QtyMax);
      }
  });
  /*** END product quantity control ***/

    var url = "http://localhost:80/FYP-Project/user/lib/location/melaka.json";
    $.getJSON(url, function(data) {
        // console.log(data);
        // Do something with the JSON data
        populateDropdown(data);
    });
});

/*** Get Melaka Location ***/
function populateDropdown(melakaData) {
    const dropdown = document.getElementById('Postcode');
    if (dropdown && melakaData.city) {
        melakaData.city.forEach(city => {
            city.postcode.forEach(postcode => {
                const option = document.createElement('option');
                option.value = postcode;
                option.textContent = `${city.name} - ${postcode}`;
                dropdown.appendChild(option);
            });
        });
    }
}
