package com.app.foxhopr.custom.view;

import android.content.Context;
import android.graphics.Typeface;
import android.util.AttributeSet;
import android.widget.Button;

/**
 * Created by a3logics on 30/07/15.
 */
public class BoldButton extends Button {


    public BoldButton(Context context, AttributeSet attrs, int defStyle) {
        super(context, attrs, defStyle);
        init();
    }

    public BoldButton(Context context, AttributeSet attrs) {
        super(context, attrs);
        init();
    }

    public BoldButton(Context context) {
        super(context);
        init();
    }

    private void init() {
        if (!isInEditMode()) {
            Typeface tf = Typeface.createFromAsset(getContext().getAssets(), "fonts/graphik_bold.ttf");
            setTypeface(tf);
        }
    }
}
