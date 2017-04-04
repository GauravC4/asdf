package com.mrc.gaurav.asdf.adapter;

import android.app.Activity;
import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.TextView;

import com.mrc.gaurav.asdf.R;
import com.mrc.gaurav.asdf.data.FeedItem;
import com.mrc.gaurav.asdf.data.FeedItem_allot;
import com.mrc.gaurav.asdf.data.FeedItem_exam;

import java.util.List;

public class FeedListAdapter_allot extends BaseAdapter {
    private Activity activity;
    private LayoutInflater inflater;
    private List<FeedItem_allot> feedItems;

    public FeedListAdapter_allot(Activity activity, List<FeedItem_allot> feedItems) {
        this.activity = activity;
        this.feedItems = feedItems;
    }

    @Override
    public int getCount() {
        return feedItems.size();
    }

    @Override
    public Object getItem(int location) {
        return feedItems.get(location);
    }

    @Override
    public long getItemId(int position) {
        return position;
    }

    @Override
    public View getView(int position, View convertView, ViewGroup parent) {

        if (inflater == null)
            inflater = (LayoutInflater) activity
                    .getSystemService(Context.LAYOUT_INFLATER_SERVICE);
        if (convertView == null)
            convertView = inflater.inflate(R.layout.feed_item_allot, null);

        TextView mScode = (TextView) convertView.findViewById(R.id.allot_scode);
        TextView mEdate = (TextView) convertView.findViewById(R.id.allot_date);
        TextView mEtime = (TextView) convertView.findViewById(R.id.allot_time);
        TextView mRoom = (TextView) convertView.findViewById(R.id.allot_room);
        TextView mPos = (TextView) convertView.findViewById(R.id.allot_position);
        TextView mName = (TextView) convertView.findViewById(R.id.allot_name);

        final FeedItem_allot item = feedItems.get(position);

        mScode.setText(item.getScode());
        mEdate.setText(item.getEdate());
        mEtime.setText(item.getEtime());
        mRoom.setText(item.getRoom());
        mPos.setText(item.getPos());
        mName.setText(item.getName());

        return convertView;
    }
}
