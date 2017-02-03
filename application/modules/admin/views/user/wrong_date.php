<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row">

	<div class="col-md-12">
		<div class="box box-danger">
			<div class="box-header with-border">
				<h3 class="box-title">عذراً .. لا تستطيع تسجيل الحضور</h3>
			</div>
			<div class="box-body">
                <b>نود الإيضاح لكم بأن هذه الفرقة تدرس في الأيام التالية فقط:</b>
                <ul style="padding-top: 7px;">
                <?php
                foreach ($dates as $date) {
                    echo "<li>$date</li>";
                }
                ?>
                </ul>
                <span>علماً بأن هذا الفصل الدراسي يقع في الفترة من <?php echo "$startDate <b>إلى</b> $endDate";  ?></span>
			</div>
            <div class="box-footer">
                <h5><span>للمزيد من التفاصيل يرجى التواصل مع الهيئة التعليمية.</span></h5>
            </div>
		</div>
	</div>
	
</div>