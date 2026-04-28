<div class="heading-page header-text">
    <section class="page-heading">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-content">
                        <h4>Contact Us</h4>
                        <h2>Let's stay in touch!</h2>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>






   <section class="contact-us">
      <div class="container">
        <div class="row">

          <div class="col-lg-12">
            <div class="down-contact">
              <div class="row">
                <div class="col-lg-8">
                  <div class="sidebar-item contact-form">
                    <div class="sidebar-heading">
                      <h2>Send us a message</h2>
                    </div>
                    <div class="content">
                    <?php if ($msg = $this->sessionController->getFlash('success')): ?>
                        <div class="alert alert-success"><?php echo $msg; ?></div>
                    <?php endif; ?>
                    <?php if ($msg = $this->sessionController->getFlash('error')): ?>
                        <div class="alert alert-danger"><?php echo $msg; ?></div>
                    <?php endif; ?>

                    <form id="contact" action="<?php echo url('contact-submit'); ?>" method="post">
                            <?php $adminSupport->csrfField(); ?>

                        <div class="row">
                            <div style="display:none;">
                                <input type="text" name="website_url" value="">
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <fieldset>
                                    <input name="name" type="text" id="name" placeholder="Your Name" >
                                </fieldset>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <fieldset>
                                    <input name="email" type="email" id="email" placeholder="Your Email"  >
                                </fieldset>
                            </div>
                            <div class="col-md-12 col-sm-12">
                                <fieldset>
                                    <input name="subject" type="text" id="subject" placeholder="Subject" >
                                </fieldset>
                            </div>
                            <div class="col-lg-12">
                                <fieldset>
                                    <textarea name="message" rows="6" id="message" placeholder="Your Message" ></textarea>
                                </fieldset>
                            </div>
                            <div class="col-lg-12">
                                <fieldset>
                                    <button type="submit" id="form-submit" class="main-button">Send Message</button>
                                </fieldset>
                            </div>
                        </div>
                    </form>
                </div>
                  </div>
                </div>
                <div class="col-lg-4">
                  <div class="sidebar-item contact-information">
                    <!-- <div class="sidebar-heading">
                      <h2>contact information</h2>
                    </div> -->

                        <div class="content">
                            <?php if (! empty($page->content)): ?>
                                <?php echo $page->content; ?>
                            <?php else: ?>
                                <div class="empty-page-state text-center" style="padding: 40px 0;">
                                    <i class="fa fa-file-text-o" style="font-size: 48px; color: #eee; margin-bottom: 20px;"></i>
                                    <h3>Content coming soon</h3>
                                    <p>We are currently updating this page. Please check back later.</p>
                                    <div class="main-button">
                                        <a href="<?php echo url('blog'); ?>">Visit our Blog</a>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-12">
            <div id="map">
              <iframe src="https://maps.google.com/maps?q=Av.+L%C3%BAcio+Costa,+Rio+de+Janeiro+-+RJ,+Brazil&t=&z=13&ie=UTF8&iwloc=&output=embed" width="100%" height="450px" frameborder="0" style="border:0" allowfullscreen></iframe>
            </div>
          </div>

        </div>
      </div>
    </section>
