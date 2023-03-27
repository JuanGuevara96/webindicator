On Error Resume Next
Dim args, objExcel

Set args = wscript.Arguments
Set objExcel = CreateObject("Excel.Application")

If Err.Number <> 0 Then
  WScript.Echo "Error in DoStep1: " & Err.Number & Err.Description
  Err.Clear
End If

objExcel.Workbooks.Open "C:\xampp\htdocs\webindicator\files\INMOBILIARIA\Div-InmobMX-2019.xlsm"
objExcel.Visible = True

If Err.Number <> 0 Then
  WScript.Echo "Error in DoStep2: " & Err.Number & Err.Description
  Err.Clear
End If


objExcel.Activeworkbook.Save
objExcel.Activeworkbook.Close(0)

If Err.Number <> 0 Then
  WScript.Echo "Error in DoStep3: " & Err.Number & Err.Description
  Err.Clear
End If

On Error Goto 0
objExcel.Quit
