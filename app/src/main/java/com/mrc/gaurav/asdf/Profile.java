package com.mrc.gaurav.asdf;

import android.app.ProgressDialog;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.preference.PreferenceManager;
import android.support.v4.app.Fragment;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ListView;
import android.widget.Toast;

import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.mrc.gaurav.asdf.adapter.FeedListAdapter;
import com.mrc.gaurav.asdf.app.AppController;
import com.mrc.gaurav.asdf.data.FeedItem;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.List;

import com.android.volley.toolbox.JsonArrayRequest;

/**
 * A simple {@link Fragment} subclass.
 */
public class Profile extends Fragment {

    private ListView listView;
    private FeedListAdapter listAdapter;
    private List<FeedItem> feedItems;
    private String URL_FEED = "http://gauravc4.16mb.com/notifications.php";

    public Profile() {
        // Required empty public constructor
    }


    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {

        SharedPreferences sp = getActivity().getSharedPreferences("your_prefs",getActivity().MODE_PRIVATE);
        if(!sp.contains("username"))
        {
            getActivity().finish();
            startActivity(new Intent(getActivity(),LoginActivity.class));

        }
        // Inflate the layout for this fragment
        View v = inflater.inflate(R.layout.fragment_profile, container, false);
        feed(v);
        Toast.makeText(this.getContext(),sp.getString("username",""),Toast.LENGTH_LONG).show();
        return v;
    }

    void feed(View v){

        listView = (ListView) v.findViewById(R.id.notification_list);

        feedItems = new ArrayList<FeedItem>();

        listAdapter = new FeedListAdapter(getActivity(), feedItems);
        listView.setAdapter(listAdapter);

        final ProgressDialog loading = ProgressDialog.show(getContext(),"Loading Data", "Please wait...",false,false);

        JsonArrayRequest jsonArrayRequest = new JsonArrayRequest(URL_FEED,
                new Response.Listener<JSONArray>() {
                    @Override
                    public void onResponse(JSONArray response) {
                        //Dismissing progress dialog
                        loading.dismiss();

                        //calling method to parse json array
                        parseJsonFeed(response);
                    }
                },
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        loading.dismiss();
                        error.printStackTrace();
                    }
                });

        AppController.getInstance().addToRequestQueue(jsonArrayRequest);

    }

    private void parseJsonFeed(JSONArray response) {

        for (int i = 0; i < response.length(); i++) {

            FeedItem item = new FeedItem();
            JSONObject feedObj = null;

            try{
                feedObj = response.getJSONObject(i);

                item.setNotificationId(feedObj.getString("nid"));
                item.setNotificationText(feedObj.getString("ntext"));
            }
            catch (JSONException e) {
                e.printStackTrace();
            }

            feedItems.add(item);
        }

        listAdapter.notifyDataSetChanged();
    }

}
