"use strict";

M.availability_courseprogress = M.availability_courseprogress || {};

M.availability_courseprogress.form = Y.Object(M.core_availability.plugin);

M.availability_courseprogress.form.initInner = function() {
    // Does nothing.
};

M.availability_courseprogress.form.getNode = function(json) {
    var html, node;

    // Create HTML structure.
    html = '';
    html += '<label for="courseprogress">';
    html += M.util.get_string('fieldlabel', 'availability_courseprogress') + '</label>';
    html += '<input type="number" name="courseprogress" id="courseprogress" min="1" max="100" step="1">';
    node = Y.Node.create('<span class="form-inline">' + html + '</span>');

    // Set initial values.
    if (json.courseprogress !== undefined) {
        node.one('input[name=courseprogress]').set('value', json.courseprogress);
    }

    // Add event handlers (first time only).
    if (!M.availability_courseprogress.form.addedEvents) {
        M.availability_courseprogress.form.addedEvents = true;
        var root = Y.one('.availability-field');
        root.delegate('change', function() {
            // Whichever dropdown changed, just update the form.
            M.core_availability.form.update();
        }, '.availability_courseprogress input[name=courseprogress]');
    }

    return node;
};

M.availability_courseprogress.form.fillValue = function(value, node) {
    value.courseprogress = node.one('input[name=courseprogress]').get('value');
};

M.availability_courseprogress.form.fillErrors = function(errors, node) {
    var value = {};
    this.fillValue(value, node);

    // Check value has been set.
    if (value.courseprogress === undefined || value.courseprogress === '' || value.courseprogress <= 0) {
        errors.push('availability_courseprogress:validnumber');
    }

    if (value.courseprogress !== undefined && value.courseprogress > 100) {
        errors.push('availability_courseprogress:validnumber');
    }
};
