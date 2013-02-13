var QueryProfileView = Backbone.View.extend({

    events: {
        "click .profile": "profile"
    },

    templates: {
        details: null
    },

    initialize: function() {
        this.templates.details = _.template($('#query_details').html());

        this.listenTo(this.collection, "select", this.render);
        this.listenTo(this.collection, "reset", this.render);

        this.render();
    },

    profile: function(e) {
        e.preventDefault();

        var query = this.collection.current();
        query.set('query', this.$('[name="query"]').val());
        query.select();

        if (!query.id) {
            this.collection.add(query);
        } else {
            query.save();
        }

    },

    render: function() {
        var html = this.templates.details({
            query: this.collection.current()
        });
        this.$('.query').html(html);
    }
});
