$(function() {
    var app = new Application();
    Backbone.history.start();
});

function serialize(form) {
    var values = form.serializeArray();
    return _.reduce(values, function(object, item) {
        object[item.name] = item.value;
        return object;
    }, {})
}

function ms(seconds, decimals) {
    var decimals = decimals || 3;
    var prefix = 1000 * Math.pow(10, decimals);
    return (Math.round(parseFloat(seconds) * 1000 * prefix) / prefix).toFixed(decimals);
}