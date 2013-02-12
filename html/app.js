var Profiler = Backbone.Router.extend({
    routes: {
        "dashboard": "dashboard",
        "connection/add": "addConnection"
    },

    initialize: function(options) {
        this.start();

        var connectionsView = new ConnectionsView({
            el: '.connections',
            collection: new Connections()
        });



//        var connections = new Connections();

//        connections2.reset([
//                    {host: "test1", database: "db1", user: "dev", password: "dev"},
//                    {host: "test2", database: "zerebral", user: "dev", password: "dev"},
//                    {host: "test3", database: "sems", user: "dev", password: "dev"},
//                    {host: "test4", database: "ttm", user: "dev", password: "dev"}
//                ]);
//
//        connections2.create({host: "test1", database: "db1", user: "dev", password: "dev"});

//        console.log(this);
//        connections.fetch();
//        console.log(connections2.models);
//        connections2.sync();
    },

    start: function() {
        console.log('start');
    },

    dashboard: function() {
        console.log('dashboard');
    },

    addConnection: function() {
        console.log('add connection');
    }
});


$(function() {
    var app = new Profiler();
    Backbone.history.start();
});

function serialize(form) {
    var values = form.serializeArray();
    return _.reduce(values, function(object, item) {
        object[item.name] = item.value;
        return object;
    }, {})
}