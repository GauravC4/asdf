package com.mrc.gaurav.asdf;

import android.app.Dialog;
import android.app.ProgressDialog;
import android.content.Intent;
import android.content.SharedPreferences;
import android.net.Uri;
import android.os.AsyncTask;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;

import java.io.BufferedReader;
import java.io.BufferedWriter;
import java.io.DataOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.OutputStreamWriter;
import java.net.HttpURLConnection;
import java.net.MalformedURLException;
import java.net.URL;

public class LoginActivity extends AppCompatActivity {

    Button login;
    private EditText editTextUserName; private EditText editTextPassword;
    String username, password;
    SharedPreferences sp;
    SharedPreferences.Editor editor ;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_login);

        login = (Button)findViewById(R.id.login_button);
        editTextUserName = (EditText) findViewById(R.id.login_username);
        editTextPassword = (EditText) findViewById(R.id.login_password);
        sp = getSharedPreferences("your_prefs", this.MODE_PRIVATE);
        editor = sp.edit();

        login.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {

                username = editTextUserName.getText().toString();
                password = editTextPassword.getText().toString(); Log.d("place","login onclick");


                if(username.length() != 0){
                    Log.d("place","initiate authentication");
                    authenticate();
                }else{
                    Log.d("place","invalid input"+username+"pass:"+password);
                    Toast.makeText(LoginActivity.this, "Invalid Input", Toast.LENGTH_SHORT).show();
                }
            }
        });
    }

    void authenticate(){

        class LoginAsync extends AsyncTask<String, Void, String> {

            private Dialog loadingDialog;

            @Override
            protected void onPreExecute() {
                super.onPreExecute();
                loadingDialog = ProgressDialog.show(LoginActivity.this, "Please wait", "Processing...");
            }

            @Override
            protected String doInBackground(String... params) {
                InputStream is = null;
                String result = null;

                URL url = null;
                try {
                    url = new URL("http://intruding-decay.000webhostapp.com/authenticate.php");
                } catch (MalformedURLException e) {
                    e.printStackTrace();
                }

                try{
                    HttpURLConnection connection = (HttpURLConnection)url.openConnection();
                    connection.setRequestMethod("POST");
                    connection.setDoOutput(true);
                    connection.setDoInput(true);

                    Uri.Builder builder = new Uri.Builder()
                            .appendQueryParameter("username", username)
                            .appendQueryParameter("password", password);
                    String query = builder.build().getEncodedQuery();


                    DataOutputStream dStream = new DataOutputStream(connection.getOutputStream());
                    BufferedWriter writer = new BufferedWriter(
                            new OutputStreamWriter(dStream, "UTF-8"));
                    writer.write(query);
                    writer.flush();
                    writer.close();
                    dStream.close();
                    connection.connect();
                    int responseCode = connection.getResponseCode();

                    if (responseCode == HttpURLConnection.HTTP_OK) {

                        BufferedReader br = new BufferedReader(new InputStreamReader(connection.getInputStream()));
                        StringBuilder sb = new StringBuilder();

                        String line = null;
                        while ((line = br.readLine()) != null) {
                            sb.append(line);
                        }
                        result = sb.toString();
                    }else{
                        result="unsucessfull";
                    }
                } catch (MalformedURLException e) {
                    e.printStackTrace(); result="Error Connecting Server"+e;
                } catch (IOException e) {
                    e.printStackTrace();
                    result = "Error Connecting Server"+e;
                }
                return result;
            }

            @Override
            protected void onPostExecute(String result){
                String[] separated = result.split(":");
                loadingDialog.dismiss();
                if(separated[0].equalsIgnoreCase("success")){

                    editor.putString("prof_det1", separated[1]);
                    editor.putString("prof_det2", separated[2]);
                    editor.commit();

                    Intent navigator = new Intent(LoginActivity.this, Navigator.class);
                    startActivity(navigator);
                    finish();

                }else {
                    Log.d("result:",result);
                    Toast.makeText(LoginActivity.this, result, Toast.LENGTH_SHORT).show();
                }
            }
        }

        Log.d("place","authenticate");
        LoginAsync la = new LoginAsync();
        la.execute(username, password);

    }

}
