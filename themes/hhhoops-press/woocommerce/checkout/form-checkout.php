<?php
/**
 * Checkout Form
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.5.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && !is_user_logged_in() ) {
  echo do_shortcode( '[woocommerce_my_account]' );
  echo '<hr/>';
	echo '<h5 class="callout medium-padding">' . apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) . '</h5>';
	return;
}

?>

<?php if ( $checkout->get_checkout_fields() ) do_action( 'g365_collect_data_fields' ); ?>

<form id="woocommerce-checkout-form" name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

	<?php if ( $checkout->get_checkout_fields() ) : ?>

    <header class="entry-header">
        <h3 class="entry-title"><?php _e( 'Checkout', 'woocommerce' ); ?></h3>
    </header>

    <?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

		<div class="grid-x grid-margin-x" id="customer_details" style="display: flex; flex-direction: column;">
			<div class="cell small-12 medium-6">
				<?php do_action( 'woocommerce_checkout_shipping' ); ?>
			</div>
			<div class="cell small-12 medium-12">
				<?php
        do_action( 'woocommerce_checkout_billing' ); ?>
			</div>

      
		</div>
     
<!--  <div class="waiver-container">
        <h2>Waiver and Release of Liability</h2>
        <div class="waiver small-margin-bottom">
            <p>In consideration of being allowed to participate in, and/or observe as a spectator, in sports and activities, 
                including but not limited to basketball and volleyball events and programs such as club teams, camps, tournaments, leagues, 
                and training, or any other sports, activities or programs (collectively “Activity”), at any facility used for the Activity 
                (the “Premises”), the below signed participant, and the participant’s parent(s) or legal guardian(s) if the participant is a 
                minor, agrees to waive, release and discharge Open Gym Premier ("OGP"), Elite Basketball Circuit ("EBC"), Grassroots 365 ("G365"), 
                Pulse, and Sports Boardroom (collectively the “Providers”) as follows:
            </p>
            <p><span class="waiver__sub-heading">MEDICAL RELEASE</span>: Pursuant to the provisions of sections 6910 et seq. 
                of the California Family Code, and other applicable laws, I hereby authorize Providers to procure and consent 
                to, medical, hospital or dental care for myself or my child in the event of injury as a result of participation in this program.</p>
            <p><span class="waiver__sub-heading">COVID-19</span>: I understand that Providers cannot prevent the presence of 
                COVID-19 at the Premises or prevent the possibility that  me or my child(ren) will be exposed to, contract with, 
                or spread COVID-19 while entering into and or utilizing the Premises. I am aware of and acknowledge the seriousness of the risks 
                associated with COVID-19. <strong>I HEREBY CHOOSE TO ACCEPT THE RISK OF CONTRACTING AND/OR SPREADING COVID-19 FOR MYSELF AND/OR MY CHILD(REN) 
                IN ORDER TO BE ALLOWED TO ENTER INTO AND UTILIZE THE PREMISES</strong>. In consideration of my participation and on behalf of myself, and any 
                of all of my family members, minor child(ren), my heirs, successors, assigns and personal representatives and each of them, having 
                voluntarily and knowingly entered the Premises, <strong>I HEREBY FOREVER RELEASE AND WAIVE MY RIGHT TO BRING SUIT AGAINST THE PROVIDERS THEIR 
                OWNERS, OFFICERS, DIRECTORS, MANAGERS, OFFICIALS, TRUSTEES, AGENTS, EMPLOYEES, AFFILIATES AND OR OTHER REPRESENTATIVES (RELEASED PARTIES) 
                IN CONNECTION WITH ANY AND ALL EXPOSURE, INFECTION, AND/OR SPREAD OF COVID-19 RELATED TO ME AND/OR MY CHILD(REN) ENTERING INTO AND OR 
                UTILIZING THE PREMISES</strong>. I understand that this waiver and release means that I have forever discharged the released parties and give up all of my right to bring any claims, actions, lawsuits, demands for 
                damages and or losses, including for personal injuries, death, disease or property losses, or any other loss, including but not limited to claims of negligence and give up any claim I or my child(ren) may have to seek damages, 
                whether known or unknown, foreseen or unforeseen to the maximum extent allowed by law.
            </p>
            <p><span class="waiver__sub-heading">PHOTO/VIDEO RELEASE</span>: I hereby agree to allow the use of my child(ren)’s photograph and/or video for publicity.</p>
            <p><span class="waiver__sub-heading">WAIVER AND RELEASE OF LIABILITY</span>: In consideration of my 
                participation, <strong>I HEREBY RELEASE, DISCHARGE AND COVENANT NOT TO SUE</strong> the Providers, their officers, employees and 
                volunteers, from any and all present and future claims, demands, actions, payment and/or reimbursement of medical expenses, or causes 
                of action resulting from any accidents, injuries, deaths, or loss of and/or damage to my/our person(s) or property arising out of or 
                connected with my/our participation in the above Activity while at the Premises. <strong>I hereby voluntarily waive any and all claims 
                    resulting from negligence</strong>, both present and future, which may be made by me, my family, estate, heirs, or assigns. 
                    Further, I am aware that this Activity may involve certain risks or possible dangers, including death, and that equipment provided 
                    for my protection may be inadequate to prevent serious injury. I am voluntarily participating in this Activity with knowledge of the 
                    danger involved and hereby agree to accept any and all inherent risks of property damage, personal injury, or death. I further agree 
                    to indemnify and hold harmless the Providers for any and all claims arising as a result of my engaging in this Activity. I 
                    understand that this waiver is intended to be as broad and inclusive as permitted by the laws of the State of California and I 
                    agree that if any portion is held invalid, the remainder of the waiver will continue in full legal force and effect. I further agree 
                    that the venue for any legal proceedings shall be in the State of California. I affirm that I am of legal age and am freely signing 
                    this document. <strong>I HAVE READ THIS FORM AND FULLY UNDERSTAND THAT BY SIGNING THIS FORM, I AM GIVING UP LEGAL RIGHTS AND/OR 
                        REMEDIES WHICH MAY BE AVAILABLE TO ME AGAINST THE PROVIDERS.</strong>The releases contained in this document are intended as a 
                        full and complete release of all liability of any nature whatsoever for all damage, injury, loss, expense, including any 
                        consequential expense, loss or damage, whether the same are now known or unknown to the undersigned, expected, or unexpected by 
                        the undersigned, or have appeared or developed, and all rights under Section 1542 of the California Civil Code are hereby 
                        expressly waived and relinquished.  Section 1542 of the California Civil Code provides as follows:
            </p>
            <blockquote>
                <p><strong>"A general release does not extend to claims which the creditor does not know or suspect to exist in his or her favor at the time of executing the release, which if known by him or her must have materially affected his or her settlement with the debtor."</strong></p>
            </blockquote>
        </div>    
 
        <script charset="utf-8" type="text/javascript" src="//js.hsforms.net/forms/v2.js"></script>
        <script>
            hbspt.forms.create({
                region: "na1",
                portalId: "9435817",
                formId: "ec7efb1b-0266-4e04-adf1-b310aa1fe016"
             });
        </script>
 
    </div> -->

    <h3 id="order_review_heading" class="medium-margin-top"><?php _e( 'Review Order', 'woocommerce' ); ?></h3>
    <?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

    <div id="order_review" class="woocommerce-checkout-review-order">
      <?php do_action( 'woocommerce_checkout_order_review' ); ?>
    </div>

    <?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
		<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

	<?php endif; ?>

</form>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
