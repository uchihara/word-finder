require 'sinatra'
require 'sinatra/reloader'

set :erb, :trim => '-'

get '/' do
  p params
  texts = params['texts'] || ''
  matrix = texts.split(/,/).map{|row|row.split(//)}
  lenghts = (params['lengths'] || []).map(&:to_i)
  nouse_dict = params['nouse_dict']=='1'
  results = []
  erb :index, locals: { texts: texts, lengths: lenghts, nouse_dict: nouse_dict, results: results }
end
