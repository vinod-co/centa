<?= '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' ?>

<assessmentItem timeDependent="false" adaptive="false" label="mylabel" title="<?=$title?>" identifier="myidentifier" xsi:schemaLocation="http://www.imsglobal.org/xsd/imsqti_v2p1 imsqti_v2p1.xsd" xmlns="http://www.imsglobal.org/xsd/imsqti_v2p1" xmlns:xi="http://www.w3.org/2001/XInclude" xmlns:lip="http://www.imsglobal.org/xsd/imslip_v1p0" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:m="http://www.w3.org/1998/Math/MathML" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
	<responseDeclaration baseType="identifier" cardinality="single" identifier="RESPONSE"/>
	<outcomeDeclaration baseType="float" cardinality="single" identifier="SCORE"/>
	<itemBody>
		<?= $headertext ?>
<? if ($question->media) : ?>
		<p><object type="<?=$question->media_type?>" data="<?=$question->media?>"/></p>
<? endif; ?>
	</itemBody>
	<responseProcessing>
		<setOutcomeValue identifier="SCORE">
			<baseValue baseType="float">0</baseValue>
		</setOutcomeValue>
	</responseProcessing>
</assessmentItem>
