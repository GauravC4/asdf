package com.mrc.gaurav.asdf;


import android.app.ProgressDialog;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ListView;
import android.widget.Toast;

import com.android.volley.Request;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.mrc.gaurav.asdf.adapter.FeedListAdapter_allot;
import com.mrc.gaurav.asdf.adapter.FeedListAdapter_exam;
import com.mrc.gaurav.asdf.app.AppController;
import com.mrc.gaurav.asdf.data.FeedItem_allot;
import com.mrc.gaurav.asdf.data.FeedItem_exam;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;


/**
 * A simple {@link Fragment} subclass.
 */
public class Allotment extends Fragment {

    private ListView listView;
    private FeedListAdapter_allot listAdapter;
    private List<FeedItem_allot> feedItems;
    private String URL_FEED = "http://gauravc4.16mb.com/allotment_display.php";
    private JSONArray result_json_array;
    private String username, user_id, type;

    public Allotment() {
        // Required empty public constructor
    }


    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        // Inflate the layout for this fragment

        SharedPreferences sp = getActivity().getSharedPreferences("your_prefs",getActivity().MODE_PRIVATE);
        if(!sp.contains("username"))
        {
            getActivity().finish();
            startActivity(new Intent(getActivity(),LoginActivity.class));

        }
        else{
            username = sp.getString("username","");
            user_id = sp.getString("prim","");
            type = sp.getString("type","");
        }
        View v =inflater.inflate(R.layout.fragment_allotment, container, false);
        feed(v);
        return v;
    }

    void feed(View v)
    {
        listView = (ListView) v.findViewById(R.id.allot_list);

        feedItems = new ArrayList<FeedItem_allot>();

        listAdapter = new FeedListAdapter_allot(getActivity(), feedItems);
        listView.setAdapter(listAdapter);

        final ProgressDialog loading = ProgressDialog.show(getContext(), "Loading Data", "Please wait...", false, false);

        StringRequest strRequest = new StringRequest(Request.Method.POST, URL_FEED,
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        try{
                            result_json_array = new JSONArray(response);

                            for (int i = 0; i < result_json_array.length(); i++) {

                                FeedItem_allot item = new FeedItem_allot();
                                JSONObject feedObj = null;

                                feedObj = result_json_array.getJSONObject(i);
                                item.setScode(feedObj.getString("scode"));
                                item.setEdate(feedObj.getString("edate"));
                                item.setEtime(feedObj.getString("etime"));
                                item.setRoom(feedObj.getString("room"));
                                item.setPos(feedObj.getString("position"));
                                item.setName(feedObj.getString("name"));

                                loading.dismiss();

                                feedItems.add(item);
                            }

                            listAdapter.notifyDataSetChanged();

                        }catch(JSONException e){
                            e.printStackTrace();
                        }
                    }
                },
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        Toast.makeText(getActivity(), error.toString(), Toast.LENGTH_LONG).show();
                    }
                }) {
            @Override
            protected Map<String, String> getParams() {
                Map<String, String> params = new HashMap<String, String>();
                params.put("prim",user_id);
                params.put("type",type);
                return params;
            }
        };
        loading.dismiss();
        AppController.getInstance().addToRequestQueue(strRequest);
    }
}
