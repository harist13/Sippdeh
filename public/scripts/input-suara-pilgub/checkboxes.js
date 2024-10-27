const checksCheckAllCheckboxes = () => document.getElementById('checkAll').checked = true;

const unchecksCheckAllCheckboxes = () => document.getElementById('checkAll').checked = false;

const syncCheckedCheckboxes = () => {
	checksCheckAllCheckboxes();

	document.querySelectorAll('.centang input[type=checkbox]')
		.forEach(checkbox => {
			const isChecked = TPS.exists(checkbox.parentElement.dataset.id);
			checkbox.checked = isChecked;

			if (!isChecked) unchecksCheckAllCheckboxes();
		});
};

function onCheckAllCheckboxesChange() {
	const isCheckAll = this.checked;
	document.querySelectorAll('.centang input[type=checkbox]')
		.forEach(checkbox => {
			checkbox.checked = isCheckAll;
			checkbox.dispatchEvent(new Event('change'));
		});
}

const onCheckboxChange = event => {
	const checkbox = event.target;
	const tpsId = checkbox.parentElement.dataset.id;

	syncCheckedCheckboxes();
};