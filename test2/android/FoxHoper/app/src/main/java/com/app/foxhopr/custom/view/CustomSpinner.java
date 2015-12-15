package com.app.foxhopr.custom.view;


import android.annotation.SuppressLint;
import android.content.Context;
import android.content.res.TypedArray;
import android.util.AttributeSet;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.AdapterView;
import android.widget.AdapterView.OnItemClickListener;
import android.widget.LinearLayout.LayoutParams;
import android.widget.ListView;
import android.widget.PopupWindow;
import android.widget.TextView;

import com.app.foxhopr.interfaces.OnItemSelectedListenerCustomSpinner;
import com.foxhoper.app.R;


/**____________________ Custom Spinner ______________________
 * 
 * - A replacement of Android widget "Spinner"
 * - Implement with a specific design like Android widget "Action Menu" 
 *   to show a Drop down list of elements.
 * - We can customize it according to own requirement
 * 
 * @author Pankaj
 *
 */
public class CustomSpinner extends TextView implements OnClickListener{
	
	private PopupWindow mPopupWindow;
	private ListView mListView;
	private Context mContext;
	private int mArray_resource_id;
	private OnItemSelectedListenerCustomSpinner onItemSelectedListenerCustomSpinner;

	public CustomSpinner(Context context, AttributeSet attrs, int defStyle) {
		super(context, attrs, defStyle);
		init(attrs);
	}
	
	public CustomSpinner(Context context, AttributeSet attrs) {
		super(context, attrs);
		init(attrs);
		
	}
	public CustomSpinner(Context context) {
		super(context);
		init(null);
	}
	
	@SuppressLint({ "InflateParams", "Recycle" })
	private void init(AttributeSet attrs) {
		if (isInEditMode())
			return;
		if (attrs!=null) {
			 TypedArray a = getContext().obtainStyledAttributes(attrs, R.styleable.CustomSpinner);
			 mArray_resource_id = a.getResourceId(R.styleable.CustomSpinner_entries, 0);
		}
		mContext = this.getContext();
		// Inflate pop up layout
		LayoutInflater layoutInflater = (LayoutInflater) mContext.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
        final View layout = layoutInflater.inflate(R.layout.layout_custom_drop_down, null);
        
        // Initialize Pop up with giving properties
        mPopupWindow  = new PopupWindow(layout,this.getMeasuredWidth(),LayoutParams.WRAP_CONTENT,true);
        //mPopupWindow.setBackgroundDrawable(mContext.getResources().getDrawable(R.mipmap.picker_bg_9));
		//mPopupWindow.setBackgroundDrawable(mContext.getResources().getDrawable(R.drawable.picker_bg));
		mPopupWindow.setBackgroundDrawable(null);
        
        mListView = (ListView) layout.findViewById(R.id.list_drop_down);
        RegistrationSupport.setLocalCustomSpinnerData(mContext, mArray_resource_id, mListView);
        this.postDelayed(new Runnable() {
			public void run() {
				mPopupWindow.setWidth(CustomSpinner.this.getMeasuredWidth());
				//mPopupWindow.setHeight(CustomSpinner.this.getMeasuredHeight()*3);
				mPopupWindow.setHeight(LayoutParams.WRAP_CONTENT);
			}
		}, 200);
        
        mListView.setOnItemClickListener(new OnItemClickListener() {

			@Override
			public void onItemClick(AdapterView<?> parent, View view,
					int position, long id) {
				CustomSpinner.this.setText(mContext.getResources().getStringArray(mArray_resource_id)[position]);
				mPopupWindow.dismiss();
				if(onItemSelectedListenerCustomSpinner!=null)
					onItemSelectedListenerCustomSpinner.OnItemSelected(parent, view, position, id);
			}
		});
        
        this.setOnClickListener(this);
	    
	}
	
	@Override
	protected void onMeasure(int widthMeasureSpec, int heightMeasureSpec) {
		super.onMeasure(widthMeasureSpec, heightMeasureSpec);
		if (isInEditMode())
			return;
		if(mPopupWindow.getWidth()<=0){
			Log.e("onMeasure", "onMeasure Called");
			mPopupWindow.setWidth(widthMeasureSpec);
			//mPopupWindow.setHeight(CustomSpinner.this.getMeasuredHeight()*3);
			mPopupWindow.setHeight(LayoutParams.WRAP_CONTENT);
		}
	}
	
	// Set selected item position
	public void setSelection(int position){
		mListView.setItemChecked(position, true);
		this.setText(mContext.getResources().getStringArray(mArray_resource_id)[position]);
	}
	
	public void setOnItemSelectedListener(OnItemSelectedListenerCustomSpinner onItemSelectedListenerCustomSpinner){
		this.onItemSelectedListenerCustomSpinner = onItemSelectedListenerCustomSpinner;
	}

	@Override
	public void onClick(View v) {
		
		mPopupWindow.showAsDropDown(v);
		/*int viewLoc[] = {0,0};
		this.getLocationInWindow(viewLoc);
		Log.e("custom spinner", "popup height : "+viewLoc[0]+" , "+viewLoc[1]);
		if(Utility.getScreenSize(mContext).y*80/100<viewLoc[1]){
			mPopupWindow.setHeight(CustomSpinner.this.getMeasuredHeight()*3);
		}else{
			mPopupWindow.setHeight(LayoutParams.WRAP_CONTENT);
		}*/
	}

}