package com.mrc.gaurav.asdf.data;

public class FeedItem {

    private String notification_text, notification_id;

    public FeedItem() {
    }

    public FeedItem(String notification_id, String notification_text) {
        super();
        this.notification_id = notification_id;
        this.notification_text = notification_text;
    }

    public String getNotificationId() {
        return notification_id;
    }

    public void setNotificationId(String id) {
        this.notification_id = id;
    }

    public String getNotificationText() {
        return notification_text;
    }

    public void setNotificationText(String address) {
        this.notification_text = address;
    }

}
