var Profiler = Backbone.Router.extend({
    routes: {

    },

    initialize: function(options) {
        var connections = new Connections();
        var queries = new Queries();

        var connectionsView = new ConnectionsView({
            el: '.connections',
            collection: connections
        });

        var favoriteQueriesView = new FavoriteQueriesView({
            el: '.favorite-queries',
            collection: queries
        });

        var recentQueriesView = new RecentQueriesView({
            el: '.recent-queries',
            collection: queries
        });

        var queryProfile = new QueryProfileView({
            el: '.query-profile',
            collection: queries
        });

        queries.fetch();

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