<?
//функция выводит именинников
####################
## http://puma.h2m.ru ##
## автор Антибиотик ##
####################

function birth($birth=NULL){
global $set;
$month = date("m");
if ($month=="1") {
if (file_exists(H."sys/birth/january")){
include_once H."sys/birth/january";
 }else{
echo'<br/>';}
 }
if ($month=="2") {
if (file_exists(H."sys/birth/february")){
include_once H."sys/birth/february";
 }else{
echo'<br/>';}
 }
if ($month=="3") {
if (file_exists(H."sys/birth/march")){
include_once H."sys/birth/march";
 }else{
echo'<br/>';}
 }
if ($month=="4") {
if (file_exists(H."sys/birth/april")){
include_once H."sys/birth/april";
 }else{
echo'<br/>';}
 }
if ($month=="5") {
if (file_exists(H."sys/birth/may")){
include_once H."sys/birth/may";
 }else{
echo'<br/>';}
 }
if ($month=="6") {
if (file_exists(H."sys/birth/june")){
include_once H."sys/birth/june";
 }else{
echo'<br/>';}
 }
if ($month=="7") {
if (file_exists(H."sys/birth/july")){
include_once H."sys/birth/july";
 }else{
echo'<br/>';}
 }
if ($month=="8") {
if (file_exists(H."sys/birth/august")){
include_once H."sys/birth/august";
 }else{
echo'<br/>';}
 }
if ($month=="9") {
if (file_exists(H."sys/birth/september")){
include_once H."sys/birth/september";
 }else{
echo'<br/>';}
 }
if ($month=="10") {
if (file_exists(H."sys/birth/october")){
include_once H."sys/birth/october";
 }else{
echo'<br/>';}
 }
if ($month=="11") {
if (file_exists(H."sys/birth/november")){
include_once H."sys/birth/november";
 }else{
echo'<br/>';}
 }
if ($month=="12") {
if (file_exists(H."sys/birth/december")){
include_once H."sys/birth/december";
 }else{
echo'<br/>';}
 }
 }
?>