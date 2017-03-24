package com.mrc.gaurav.asdf;


import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.preference.PreferenceManager;
import android.support.v4.app.Fragment;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Toast;


/**
 * A simple {@link Fragment} subclass.
 */
public class Exam_Details extends Fragment {


    private String username;

    public Exam_Details() {
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
        else{
            username = sp.getString("username","");
        }

        // Inflate the layout for this fragment
        Toast.makeText(this.getContext(),sp.getString("username",""),Toast.LENGTH_LONG).show();
        return inflater.inflate(R.layout.fragment_exam__details, container, false);
    }

}
