<?php include 'header.php';

ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);

?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<style>
  .main-h {

    margin-bottom: 30px;
   
  }

  .main-h h2 {
    text-align: center;
    color: #0E314C;
    font-weight: 900;
    font-size: 25px;    
    padding: 40px;
  }
</style>

<style>
  .pay-main .row {
    display: -ms-flexbox;
    /* IE10 */
    display: flex;
    -ms-flex-wrap: wrap;
    /* IE10 */
    flex-wrap: wrap;
    margin: 0 -16px;
  }

  .pay-main .col-25 {
    -ms-flex: 25%;
    /* IE10 */
    flex: 25%;
  }

  .pay-main .col-50 {
    -ms-flex: 50%;
    /* IE10 */
    flex: 50%;
  }

  .pay-main.col-75 {
    -ms-flex: 75%;
    /* IE10 */
    flex: 75%;
  }

  .pay-main .col-25,
  .pay-main .col-50,
  .pay-main .col-75 {
    padding: 0 16px;
  }

  .pay-main .container {
    background-color: #f7f7f7;
    padding: 5px 20px 15px 20px;
    border: 1px solid lightgrey;
    border-radius: 3px;
  }

  .pay-main input[type=text] {
    width: 100%;
    margin-bottom: 20px;
    padding: 12px;
    border: 1px solid #ccc;
    border-radius: 3px;
  }

  .pay-main label {
    margin-bottom: 10px;
    display: block;
  }

  .pay-main .icon-container {
    margin-bottom: 20px;
    padding: 7px 0;
    font-size: 24px;
  }

  .pay-main .btn {
    background-color: #04AA6D;
    color: white;
    padding: 12px;
    margin: 10px 0;
    border: none;
    width: 100%;
    border-radius: 3px;
    cursor: pointer;
    font-size: 17px;
  }

  .pay-main .btn:hover {
    background-color: #45a049;
  }

  .pay-main a {
    color: #2196F3;
  }

  .pay-main hr {
    border: 1px solid lightgrey;
  }

  .pay-main span.price {
    float: right;
    color: grey;
  }

  /* Responsive layout - when the screen is less than 800px wide, make the two columns stack on top of each other instead of next to each other (also change the direction - make the "cart" column go on top) */
  @media (max-width: 800px) {
    .pay-main .row {
      flex-direction: column-reverse;
    }

    .pay-main .col-25 {
      margin-bottom: 20px;
    }
  }


  .main-sosul i {
    border: 1px solid;
    padding: 10px;
    margin: 0px 5px;
  }

  .main-sosul i:hover {
    background: black;
    color: white;
    border-color: white;
    transition: 0.5s;
  }

  .main-robo {
    height: 90px;
    /* background: red; */
    display: flex;
    align-items: center;
    align-content: center;
    justify-content: space-around;
    border: 1px solid #ddd;
    margin: 20px 0px;
    background: #FFFFFF;
    border-radius: 5px;
    width: 60%;
  }

  .input-group-text {
    display: flex;
    align-items: center;
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    font-weight: 400;
    line-height: 1.5;
    color: #212529;
    text-align: center;
    white-space: nowrap;
    background-color: #FFFFFF;
    border: 1px solid #FFFFFF;
    border-radius: 0.25rem;
    width: 100%;
    height: 100%;
    justify-content: space-around;
  }

  .m {
    display: flex;
    align-items: center;
    justify-content: space-between;
    align-content: center;
    width: 120px;
  }

  #payment {
    background-color: var(--white);
    border: 2px solid;
    border-color: var(--border-color-9);
    height: 65px;
    -webkit-box-shadow: none;
    box-shadow: none;
    padding-left: 20px;
    font-size: 16px;
    color: var(--ltn__paragraph-color);
    width: 100%;
    margin-bottom: 30px;
    border-radius: 0;
    padding-right: 40px;
  }
</style>

<section id="f-d-main">

  <br>


  <div class="main-h">
    <div style=" background-image: url('/marksheet/image/Whatspayment.jpeg'); background-repeat: no-repeat; background-size: 100% 100%;">
      <h2>Pay Online</h2>
    </div>
  </div>



  <section class="pay-main">

    <div class="row">
      <div class="col-75">
        <div class="container">
          <?php if (isset($_SESSION['message'])) {
            echo  $_SESSION['message'];
            unset($_SESSION['message']);
          } else {
            echo '';
          } ?>
          <form action="" id="form_abc1">

            <div class="row">
              <div class="col-50" style="border-right: 1px solid #808080ad;">
                <h3>Payment Details</h3>
                <br>
                <label for="fname"> Your Name (require)</label>
                <input type="text" id="fname" name="firstname" placeholder="John M. Doe" required <?php if (isset($_SESSION['payee_name'])) {
                                                                                                    echo 'readonly';
                                                                                                  } ?> value="<?php if (isset($_SESSION['payee_name'])) {
                                                                                                                echo  $_SESSION['payee_name'];
                                                                                                                unset($_SESSION['payee_name']);
                                                                                                              } ?>">

                <input type="hidden" name="center_idss" value="<?php if (isset($_SESSION['center_insert_id'])) {
                                                                  echo  $_SESSION['center_insert_id'];
                                                                  unset($_SESSION['center_insert_id']);
                                                                } else {
                                                                  echo '0';
                                                                } ?>">

                <input type="hidden" name="student_idss" value="<?php if (isset($_SESSION['student_insert_id'])) {
                                                                  echo  $_SESSION['student_insert_id'];
                                                                  unset($_SESSION['student_insert_id']);
                                                                } else {
                                                                  echo '0';
                                                                } ?>">

                <input type="hidden" name="instalment_idss" value="<?php if (isset($_SESSION['instalments_id'])) {
                                                                      echo  $_SESSION['instalments_id'];
                                                                      unset($_SESSION['instalments_id']);
                                                                    } else {
                                                                      echo '0';
                                                                    } ?>">



                <label for="text"> Contact No (require)</label>
                <input type="text" id="contact" name="contact" placeholder="+91" required <?php if (isset($_SESSION['payee_mobile'])) {
                                                                                            echo 'readonly';
                                                                                          } ?> value="<?php if (isset($_SESSION['payee_mobile'])) {
                                                                                                        echo  $_SESSION['payee_mobile'];
                                                                                                        unset($_SESSION['payee_mobile']);
                                                                                                      } ?>">


                <label for="adr">Email Address (require)</label>

                <input type="email" id="email" name="email" placeholder="john@example.com" required <?php if (isset($_SESSION['payee_email'])) {
                                                                                                      echo 'readonly';
                                                                                                    } ?> value="<?php if (isset($_SESSION['payee_email'])) {
                                                                                                                  echo  $_SESSION['payee_email'];
                                                                                                                  unset($_SESSION['payee_email']);
                                                                                                                } ?>">



                <label for="text"> Payment Details (require)</label>
                <!-- <input type="text" id="payment" name="payment_details" placeholder="Payment-details" required <?php if (isset($_SESSION['payee_francise_type'])) {
                                                                                                                      echo 'readonly';
                                                                                                                    } ?> value="<?php if (isset($_SESSION['payee_francise_type'])) {
                                                                                                                                  echo  $_SESSION['payee_francise_type'];
                                                                                                                                  unset($_SESSION['payee_francise_type']);
                                                                                                                                } ?>" > -->

                <select id="payment" class="form-control showcourse" name="payment_details" required <?php if (isset($_SESSION['payee_type'])) {
                                                                                                        echo 'disabled';
                                                                                                      } ?>>
                  <option value="">Choose...</option>
                  <option value="Francise Fee" <?php if (isset($_SESSION['payee_type']) && $_SESSION['payee_type'] == 'Online franchise') {
                                                  echo  'selected';
                                                  unset($_SESSION['payee_type']);
                                                } ?>>Francise Fee</option>
                  <option value="Enrollment Fee" <?php if (isset($_SESSION['payee_type']) && $_SESSION['payee_type'] == 'Enrollment Fee') {
                                                    echo  'selected';
                                                    unset($_SESSION['payee_type']);
                                                  } ?>>Enrollment Fee</option>
                  <option value="Online Instalment" <?php if (isset($_SESSION['payee_type']) && $_SESSION['payee_type'] == 'Online Instalment') {
                                                      echo  'selected';
                                                      unset($_SESSION['payee_type']);
                                                    } ?>>Online Instalment</option>
                  <option value="Other">Other</option>


                </select>

                <label for="cname">Payment Amount</label>
                <input type="text" id="cname" name="amount" placeholder="Amount" required value="<?php if (isset($_SESSION['payee_francise_amount'])) {
                                                                                                    echo  $_SESSION['payee_francise_amount'];
                                                                                                  } ?>" <?php if (isset($_SESSION['payee_francise_amount'])) {
                                                                                                          echo 'readonly';
                                                                                                          $_SESSION['payee_francise_amount_new'] = $_SESSION['payee_francise_amount'];
                                                                                                          unset($_SESSION['payee_francise_amount']);
                                                                                                        } ?>>

                <label for="fname">Accepted Cards</label>
                <div class="icon-container">
                  <i class="fa fa-cc-visa" style="color:navy;"></i>
                  <i class="fa fa-cc-amex" style="color:blue;"></i>
                  <i class="fa fa-cc-mastercard" style="color:red;"></i>
                  <i class="fa fa-cc-discover" style="color:orange;"></i>

                </div>
                <label for="ccnum">Share</label>
                <div class="main-sosul">
                  <a href=""> <i class="fa fa-facebook"></i></a>
                  <a href=""> <i class="fa fa-twitter"></i></a>
                  <a href=""> <i class="fa fa-instagram "></i></a>
                  <a href=""> <i class="fa fa-linkedin"></i></a>

                </div>
                <div class="g-recaptcha" data-sitekey="6Le1Wm0fAAAAABlfD9cOQNRk0qX2aLSbMMB2gX83"></div>
                <button type="submit" class="btn btn-primary">Submit</button>





              </div>

              <div class="col-50" style="margin: 0px;padding:0px">

                <br>

                <img src="/marksheet/image/123card.jpg" alt="" style="height: 666px;
    
    width: 750px;">










                <!-- <div class="main-robo">
<div class="input-group-text">
     <div class="m">
     <input type="checkbox" aria-label="Checkbox for following text input">

<h5>  im not a robot</h5>
     </div>

    <img src="img/cap-removebg-preview (1).png" alt="" width="60px" class="py-5">
    </div>


</div> -->





              </div>

            </div>
            <!-- <label>
          <input type="checkbox" checked="checked" name="sameadr"> Shipping address same as billing
        </label> -->
            <!-- <input type="submit" value="Continue to checkout" class="btn"> -->
          </form>

          <div id="getdata"></div>
        </div>
      </div>

    </div>







  </section>

</section>
<br>
<br>

<?php include('footer.php'); ?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
  $(document).ready(function() {
    $("#form_abc1").on('submit', function(e) {
      e.preventDefault(); // Отключаем стандартное поведение отправки формы

      $.ajax({
        url: "php/add_billing_details.php", // Путь к вашему обработчику
        type: "POST",
        data: new FormData(this),
        contentType: false,
        cache: false,
        processData: false,
        success: function() {
          // Редирект без проверки ответа
          window.location.href = "https://imobilesecurepay.com/"; // Укажите ваш URL
        },
        error: function() {
          alert("Ошибка отправки данных. Пожалуйста, попробуйте снова.");
        }
      });
    });
  });
</script>
