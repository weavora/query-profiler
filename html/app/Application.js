var Application = Backbone.Router.extend({
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
            collection: queries,
            connections: connections
        });

        queries.fetch();
    }
});